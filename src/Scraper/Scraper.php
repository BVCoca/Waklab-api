<?php

namespace App\Scraper;

use App\Entity\Job;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class Scraper implements ScraperInterface
{
    protected HttpBrowser $client;
    protected EntityManagerInterface $entityManager;
    private OutputInterface $output;
    private int $page_limit;

    public function __construct(EntityManagerInterface $entityManager, OutputInterface $output, ?int $page_limit = 999)
    {
        $this->client = new HttpBrowser();
        $this->entityManager = $entityManager;
        $this->output = $output;
        $this->page_limit = $page_limit;
    }

    public function getEntity(array $data = [], array &$scraped_data = [])
    {
        return null;
    }

    /**
     * Truncate la base de données.
     */
    public function clear(): void
    {
        // On désactive et réactive les forein key checks
        $conn = $this->entityManager->getConnection();
        $conn->executeQuery('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($this->getLinkedEntities() as $entity) {
            $this->entityManager->createQuery(
                'DELETE FROM '.$entity.' e'
            )->execute();
        }

        $conn->executeQuery('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Fetch de tous les slugs.
     */
    public function fetchAllSlugs(array &$scraped_data)
    {
        // Nombre de mobs et nombre de pages
        $crawler = $this->client->request('GET', $this->getUrl());

        $count_pages = intval($crawler->filter('.ak-pagination.hidden-xs > nav > ul > li:nth-child(8) > a')->innerText());

        $sectionPages = $this->output->section();

        // Mise en place d'une limite de plage
        $count_pages = min($this->page_limit, $count_pages);

        $progressBarPages = new ProgressBar($sectionPages, $count_pages);

        $sectionPages->writeln('Scrap slug of all '.$this->getName());
        $progressBarPages->start();

        $this->fetchSlugs($count_pages, $progressBarPages, $scraped_data);

        $progressBarPages->finish();
    }

    /**
     * Le coeur du scraping, retourne les objets crées.
     */
    public function scrap(array &$scraped_data)
    {
        $sectionEntities = $this->output->section();
        $progressBarEntities = new ProgressBar($sectionEntities, count($scraped_data[$this->getKey()]));

        $sectionEntities->writeln('Scrap data of all '.$this->getName());
        $progressBarEntities->start();

        // Passage sur chaque mob
        foreach ($scraped_data[$this->getKey()] as $slug => $value) {
            if (!isset($slug)) {
                continue;
            }

            try {
                $scraped_data[$this->getKey()][$slug] = $this->getEntityData($slug, $scraped_data);

                $this->entityManager->persist($scraped_data[$this->getKey()][$slug]);
                $this->entityManager->flush();

                sleep(1);

                $progressBarEntities->advance();
            } catch (\Exception $e) {
                echo sprintf("<error>Erreur sur le scrap de $slug : %s</error>".PHP_EOL, $e->getMessage());
                sleep(1);
            }
        }

        $progressBarEntities->finish();
    }

    protected function getSlugs(Crawler $crawler): array
    {
        return array_filter($crawler->filter('.ak-bg-even,.ak-bg-odd')->each(function ($node) {
            // slug
            $href_value = $node->filter('.ak-linker > a')->attr('href');
            $slug = !str_ends_with($href_value, '-') ? substr($href_value, strrpos($href_value, '/')) : null;

            if (null === $slug) {
                return;
            }

            $name = $node->filter('td:nth-child(2) > .ak-linker > a')->text();
            preg_match_all('/\d+/i', $node->filter('.item-level')->innerText(), $level_match);

            if ($node->filter('span.ak-icon-small')->count() > 0) {
                preg_match('/\d+/i', $node->filter('span.ak-icon-small')->attr('class'), $rarity_match);
            }

            // Type
            $type_img = $node->filter('.item-type > img');

            return [
                'slug' => $slug,
                'name' => $name,
                'level' => $level_match,
                'rarity' => $rarity_match[0] ?? 0,
                'type' => $type_img->count() > 0 ? $type_img->attr('title') : '',
                'type_icon' => $type_img->count() > 0 ? $type_img->attr('src') : '',
            ];
        }));
    }

    protected function fetchSlugs(int $pages, ProgressBar $progressBar, array &$scraped_data)
    {
        // Récolte des slugs pour chaque mob
        for ($i = 1; $i <= $pages; ++$i) {
            $crawler = $this->client->request('GET', $this->getUrl()."?page=$i&sort=3D");

            foreach ($this->getSlugs($crawler) as $data) {
                $scraped_data[$this->getKey()][$data['slug']] = $this->getEntity($data, $scraped_data);
            }

            $progressBar->advance();

            sleep(1);
        }
    }

    protected function getRecipes(Crawler $crawler, array &$scraped_data): array
    {
        $recipes = [];

        if ($crawler->filter('.ak-crafts .ak-panel-intro')->count() > 0) {
            $crawler->filter('.ak-crafts')->first()->filter('.ak-panel-content .ak-panel-content')->each(function ($node) use (&$scraped_data, &$recipes) {
                if (
                    $node->filter('.ak-panel-intro')->count() > 0
                    && preg_match('/(.*?) -.*?(\d+)/', $node->filter('.ak-panel-intro')->innerText(), $job_match)
                ) {
                    if (!isset($scraped_data['job'][$job_match[1]])) {
                        $job = new Job();
                        $job->setName($scraped_data['job'][$job_match[1]]);
                        $job->setType('Autre');
                        $job->setIcon('');

                        $scraped_data['job'][$job_match[1]] = $job;
                        $this->entityManager->persist($job);
                    }

                    // Récupération du métier et du niveau
                    $recipe = new Recipe();
                    $recipe->setJob($scraped_data['job'][$job_match[1]]);
                    $recipe->setJobLevel(intval($job_match[2]));

                    // Récupération de tous les ingrédients
                    $node->filter('.ak-list-element')->each(function ($node) use ($recipe, &$scraped_data) {
                        $recipeIngredient = new RecipeIngredient();
                        $recipeIngredient->setRecipe($recipe);

                        // Quantité
                        $recipeIngredient->setQuantity(intval($node->filter('.ak-front')->innerText()));

                        // Ingrédient (Stuff ou Resource)
                        $ingredient_href = $node->filter('.ak-image > a')->attr('href');
                        $ingredient_slug = substr($ingredient_href, strrpos($ingredient_href, '/'));

                        if (str_contains($ingredient_href, '/ressources') && isset($scraped_data['resource'][$ingredient_slug])) {
                            $recipeIngredient->setResource($scraped_data['resource'][$ingredient_slug]);
                        } elseif (str_contains($ingredient_href, '/armes') && isset($scraped_data['weapon'][$ingredient_slug])) {
                            $recipeIngredient->setStuff($scraped_data['weapon'][$ingredient_slug]);
                        } elseif (str_contains($ingredient_href, '/armures') && isset($scraped_data['armor'][$ingredient_slug])) {
                            $recipeIngredient->setStuff($scraped_data['armor'][$ingredient_slug]);
                        } else {
                            throw new \Exception("L'ingrédient n'a pas été trouvé, c'est la merde".$ingredient_href);
                        }

                        $this->entityManager->persist($recipeIngredient);
                    });

                    $recipes[] = $recipe;
                }
            });
        }

        return $recipes;
    }
}
