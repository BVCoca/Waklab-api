<?php

namespace App\Scraper;

use App\Entity\Rarity;

class RarityScraper extends Scraper
{
    public function getUrl(): string
    {
        return '';
    }

    public function getKey(): string
    {
        return 'rarity';
    }

    public function getEntity(array $data = [], array &$scraped_data = [])
    {
        return Rarity::class;
    }

    public function getLinkedEntities(): array
    {
        return [
            Rarity::class,
        ];
    }

    public function getName(): string
    {
        return 'Rarity';
    }

    public function fetchAllSlugs(array &$scraped_data)
    {
        $datas = [
            [
                'Qualité commune',
                'https://waklaboratory.fr/images/rarity/gris.png',
            ],
            [
                'Inhabituel',
                'https://waklaboratory.fr/images/rarity/blanc.png',
            ],
            [
                'Rare',
                'https://waklaboratory.fr/images/rarity/vert.png',
            ],
            [
                'Mythique',
                'https://waklaboratory.fr/images/rarity/orange.png',
            ],
            [
                'Légendaire',
                'https://waklaboratory.fr/images/rarity/jaune.png',
            ],
            [
                'Epique',
                'https://waklaboratory.fr/images/rarity/rose.png',
            ],
            [
                'Relique',
                'https://waklaboratory.fr/images/rarity/violet.png',
            ],
            [
                'Souvenir',
                'https://waklaboratory.fr/images/rarity/bleu.png',
            ],
        ];

        foreach ($datas as $key => $data) {
            $obj = new Rarity();
            $obj->setName($data[0]);
            $obj->setIcon($data[1]);
            $obj->setValue($key);

            $scraped_data['rarity'][$key] = $obj;

            $this->entityManager->persist($obj);
            $this->entityManager->flush();
        }
    }

    public function scrap(array &$scraped_data)
    {
    }

    /**
     * Pas utilisée.
     */
    public function getEntityData(string $slug, array &$scraped_data = [])
    {
    }
}
