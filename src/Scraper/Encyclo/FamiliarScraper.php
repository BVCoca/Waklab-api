<?php

namespace App\Scraper\Encyclo;

use App\Entity\Caracteristic;
use App\Entity\Stuff;
use App\Entity\StuffCaracteristic;
use App\Entity\TypeStuff;
use Symfony\Component\Console\Helper\ProgressBar;

class FamiliarScraper extends Scraper
{
    public function getUrl(): string
    {
        return 'https://www.wakfu.com/fr/mmorpg/encyclopedie/familiers';
    }

    public function getKey(): string
    {
        return 'familiar';
    }

    public function getEntity(array $data = [], array &$scraped_data = [])
    {
        $familiar = new Stuff();
        $familiar->setEncyclopediaId($data['id']);
        $familiar->setName($data['name'] ?? 'Sans nom');
        $familiar->setImageUrl($data['image']);
        $familiar->setLevel(0);
        $familiar->setRarity($scraped_data['rarity'][$data['rarity']]);

        if (!isset($scraped_data['type_stuff'][$data['type']])) {
            $typeStuff = new TypeStuff();
            $typeStuff->setName($data['type']);
            $typeStuff->setIcon($data['type_icon']);

            $this->entityManager->persist($typeStuff);

            $scraped_data['type_stuff'][$data['type']] = $typeStuff;
        }

        $familiar->setType($scraped_data['type_stuff'][$data['type']]);

        return $familiar;
    }

    public function getLinkedEntities(): array
    {
        return [
            Stuff::class,
            TypeStuff::class,
            StuffCaracteristic::class,
            Caracteristic::class,
        ];
    }

    public function getName(): string
    {
        return 'Familiar';
    }

    /**
     * Fetch de tous les slugs
     */
    public function fetchAllSlugs(array &$scraped_data) {
        // Nombre de mobs et nombre de pages
        $crawler = $this->client->request('GET', $this->getUrl());

        $count_pages = intval($crawler->filter('.ak-pagination.hidden-xs > nav > ul > li:nth-child(7) > a')->innerText());

        $sectionPages = $this->output->section();

        // Mise en place d'une limite de plage
        $count_pages = min($this->page_limit, $count_pages);

        $progressBarPages = new ProgressBar($sectionPages, $count_pages);

        $sectionPages->writeln('Scrap slug of all ' . $this->getName());
        $progressBarPages->start();

        $this->fetchSlugs($count_pages, $progressBarPages, $scraped_data);

        $progressBarPages->finish();
    }

    public function getEntityData(string $slug, array &$scraped_data = [])
    {
        $familiar = $scraped_data[$this->getKey()][$slug];

        $crawler = $this->client->request('GET', $this->getUrl().$slug);

        // Image
        $familiar->setImageUrl($crawler->filter('.ak-encyclo-detail-illu > img.img-maxresponsive')->attr('src'));

        // Description
        $path_description = 'div.col-sm-9 > div >div.ak-container.ak-panel > div.ak-panel-title + div.ak-panel-content';
        if ($crawler->filter($path_description)->count() > 0) {
            $familiar->setDescription($crawler->filter($path_description)->innerText());
        }

        // Lecture des blocs d'effet, effets critique et carac
        $crawler->filter('div.ak-container.ak-panel.no-padding')->each(function ($node) use ($familiar, &$scraped_data) {
            switch (trim($node->children()->first()->text())) {
                case 'Caractéristiques':
                    $node->filter('div.ak-level-50 div.ak-title')->each(function ($node) use ($familiar, &$scraped_data) {
                        $carac_data = explode(' ', $node->innerText(), 2);

                        if (!isset($carac_data[1])) {
                            return;
                        }

                        if (!isset($scraped_data['carac'][$carac_data[1]])) {
                            $c = new Caracteristic();
                            $c->setName($carac_data[1]);

                            $this->entityManager->persist($c);

                            $scraped_data['carac'][$carac_data[1]] = $c;
                        }

                        $familiar_carac = new StuffCaracteristic();
                        $familiar_carac->setCaracteristic($scraped_data['carac'][$carac_data[1]]);
                        $familiar_carac->setStuff($familiar);
                        $familiar_carac->setValue(intval($carac_data[0]));

                        $this->entityManager->persist($familiar_carac);
                    });
                    break;
            }
        });

        return $familiar;
    }
}
