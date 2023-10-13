<?php

namespace App\Scraper;

use Symfony\Component\BrowserKit\HttpBrowser;
use Doctrine\ORM\EntityManagerInterface;
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
        foreach($this->getEntities() as $entity)
        $this->entityManager->createQuery(
            'DELETE FROM ' . $entity . ' e'
        )->execute();
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

        $entities_slugs = [];

        // Récolte des slugs pour chaque mob
        for($i = 1; $i <= $count_pages; $i++) {

            $crawler = $this->client->request('GET', $this->getUrl() . "?page=$i");

            array_push($entities_slugs, ...$this->getSlugs($crawler));

            $progressBarPages->advance();

            sleep(1);
        }

        $entities_slugs = array_unique($entities_slugs);

        $progressBarPages->finish();
        $sectionPages->clear();

        $sectionEntities = $this->output->section();
        $progressBarEntities = new ProgressBar($sectionEntities, $count_entities);

        $sectionEntities->writeln('Scrap data of all ' . $this->getName());
        $progressBarEntities->start();

        $entities = [];

        // Passage sur chaque mob
        foreach($entities_slugs as $key => $slug) {

            if(!isset($slug)) {
                continue;
            }

            $entities[$key] = $this->getEntityData($slug, $scraped_data);

            $this->entityManager->persist($entities[$key]);
            $this->entityManager->flush();

            sleep(1);

            $progressBarEntities->advance();
        }

        $progressBarEntities->finish();

        return $entities;
    }

    protected function getSlugs(Crawler $crawler) : array {
        return $crawler->filter('.ak-linker > a')->each(fn($a) => !str_ends_with($a->attr('href'), '-') ? substr($a->attr('href'), strrpos($a->attr('href'), '/')) : null);
    }
}  