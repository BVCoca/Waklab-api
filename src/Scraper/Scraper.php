<?php

namespace App\Scraper;

use Symfony\Component\BrowserKit\HttpBrowser;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class Scraper implements ScraperInterface {
    protected HttpBrowser $client;
    protected EntityManagerInterface $entityManager;
    private OutputInterface $output;

    public function __construct(EntityManagerInterface $entityManager, OutputInterface $output)
    {
        $this->client = new HttpBrowser();
        $this->entityManager = $entityManager;
        $this->output = $output;
    }

    /**
     * Truncate la base de données
     */
    public function clear(): void {

        // On désactive et réactive les forein key checks
        $conn = $this->entityManager->getConnection();
        $conn->executeQuery('SET FOREIGN_KEY_CHECKS=0;');

        foreach($this->getEntities() as $entity) {
            $this->entityManager->createQuery(
                'DELETE FROM ' . $entity . ' e'
            )->execute();
        }

        $conn->executeQuery('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Retourne les données relatives au sommaire
     */
    public function getSummary() : array {
        $summary = [];

        foreach($this->getEntities() as $entity) {
            $count = $this->entityManager->getRepository($entity)->createQueryBuilder('u')
                ->select('count(u.id)')
                ->getQuery()
                ->getSingleScalarResult();

            $summary[] = [
                $entity,
                $count
            ];
        }

        return $summary;
    }

    /**
     * Le coeur du scraping, retourne les objets crées
     */
    public function scrap(array $scraped_data): array {
        // Nombre de mobs et nombre de pages
        $crawler = $this->client->request('GET', $this->getUrl());

        $count_entities = intval($crawler->filter('.ak-list-info > strong')->innerText());
        $count_pages = intval($crawler->filter('.ak-pagination.hidden-xs > nav > ul > li:nth-child(8) > a')->innerText());

        $sectionPages = $this->output->section();

        $progressBarPages = new ProgressBar($sectionPages, $count_pages);

        $sectionPages->writeln('Scrap slug of all ' . $this->getName());
        $progressBarPages->start();

        $entities_slugs = $this->fetchSlugs($count_pages, $progressBarPages);

        $progressBarPages->finish();
        $sectionPages->clear();

        $sectionEntities = $this->output->section();
        $progressBarEntities = new ProgressBar($sectionEntities, $count_entities);

        $sectionEntities->writeln('Scrap data of all ' . $this->getName());
        $progressBarEntities->start();

        $entities = [];

        // Passage sur chaque mob
        foreach($entities_slugs as $slug) {

            if(!isset($slug)) {
                continue;
            }

            try {
                $entities[$slug] = $this->getEntityData($slug, $scraped_data);

                $this->entityManager->persist($entities[$slug]);
                $this->entityManager->flush();

                sleep(1);

                $progressBarEntities->advance();
            } catch (Exception $e) {
                echo "<error>Erreur sur le scrap de $slug</error>" . PHP_EOL;
            }
        }

        $progressBarEntities->finish();

        return $entities;
    }

    protected function getSlugs(Crawler $crawler) : array {
        return $crawler->filter('.ak-linker > a')->each(fn($a) => !str_ends_with($a->attr('href'), '-') ? substr($a->attr('href'), strrpos($a->attr('href'), '/')) : null);
    }

    protected function fetchSlugs(int $pages, ProgressBar $progressBar) : array {

        $entities_slugs = [];

        // Récolte des slugs pour chaque mob
        for($i = 1; $i <= $pages; $i++) {

            $crawler = $this->client->request('GET', $this->getUrl() . "?page=$i");

            array_push($entities_slugs, ...$this->getSlugs($crawler));

            $progressBar->advance();

            sleep(1);
        }

        $entities_slugs = array_unique($entities_slugs);

        return $entities_slugs;
    }
}  