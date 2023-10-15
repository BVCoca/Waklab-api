<?php

namespace App\Scraper;

use App\Entity\Job;

class JobScraper extends Scraper {

    public function getUrl(): string {
        return '';
    }

    public function getKey() : string {
        return 'job';
    }

    public function getEntity() {
        return new Job();
    }

    public function getLinkedEntities() : array
    {
        return [
            Job::class
        ];
    }

    public function getName() : string
    {
        return 'Jon';
    }

    public function fetchAllSlugs(array &$scraped_data) {
        // On surcharge pour ne rien faire
    }

    public function scrap(array &$scraped_data): array {
        $datas = [
            [
                'Forestier',
                '',
                'collecte'
            ],
            [
                'Herboriste',
                '',
                'collecte'
            ],
            [
                'Mineur',
                '',
                'collecte'
            ],
            [
                'Paysan',
                '',
                'collecte'
            ],
            [
                'Pêcheur',
                '',
                'collecte'
            ],
            [
                'Trappeur',
                '',
                'collecte'
            ],
            [
                'Armurier',
                '',
                'artisanat'
            ],
            [
                'Bijoutier',
                '',
                'artisanat'
            ],
            [
                'Boulanger',
                '',
                'artisanat'
            ],
            [
                'Cuisinier',
                '',
                'artisanat'
            ],
            [
                'Ébéniste',
                '',
                'artisanat'
            ],
            [
                'Maitre d\'Armes',
                '',
                'artisanat'
            ],
            [
                'Maroquinier',
                '',
                'artisanat'
            ],
            [
                'Tailleur',
                '',
                'artisanat'
            ],
        ];

        return array_map(function($data) use(&$scraped_data) {
            $obj = new Job();
            $obj->setName($data[0]);
            $obj->setIcon($data[1]);
            $obj->setType($data[2]);

            $scraped_data['job'][$data[0]] = $obj;

            $this->entityManager->persist($obj);
            $this->entityManager->flush();

            return $obj;
        }, $datas);
    }

    /**
     * Pas utilisée
     */
    public function getEntityData(string $slug, array &$scraped_data = []) {
       
    }
}