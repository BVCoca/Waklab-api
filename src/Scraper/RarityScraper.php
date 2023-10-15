<?php

namespace App\Scraper;

use App\Entity\Rarity;

class RarityScraper extends Scraper {

    public function getUrl(): string {
        return '';
    }

    public function getKey() : string {
        return 'rarity';
    }

    public function getEntity() {
        return Rarity::class;
    }

    public function getLinkedEntities() : array
    {
        return [
            Rarity::class
        ];
    }

    public function getName() : string
    {
        return 'Rarity';
    }

    public function fetchAllSlugs(array &$scraped_data) {
        // On surcharge pour ne rien faire
    }

    public function scrap(array &$scraped_data): array {
        $datas = [
            [
                'Qualité commune',
                '',
            ],
            [
                'Inhabituel',
                '',
            ],
            [
                'Rare',
                '',
            ],
            [
                'Mythique',
                '',
            ],
            [
                'Légendaire',
                '',
            ],
            [
                'Relique',
                '',
            ],
            [
                'Souvenir',
                '',
            ],
            [
                'Epique',
                '',
            ]
        ];

        return array_map(function($data, $key) use(&$scraped_data) {
            $obj = new Rarity();
            $obj->setName($data[0]);
            $obj->setIcon($data[1]);
            $obj->setValue($key);

            $scraped_data['rarity'][$key] = $obj;

            $this->entityManager->persist($obj);
            $this->entityManager->flush();

            return $obj;
        }, $datas, array_keys($datas));
    }

    /**
     * Pas utilisée
     */
    public function getEntityData(string $slug, array &$scraped_data = []) {
       
    }
}