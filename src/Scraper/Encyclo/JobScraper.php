<?php

namespace App\Scraper\Encyclo;

use App\Entity\Job;

class JobScraper extends Scraper
{
    public function getUrl(): string
    {
        return '';
    }

    public function getKey(): string
    {
        return 'job';
    }

    public function getEntity(array $data = [], array &$scraped_data = [])
    {
        return new Job();
    }

    public function getLinkedEntities(): array
    {
        return [
            Job::class,
        ];
    }

    public function getName(): string
    {
        return 'Job';
    }

    public function fetchAllSlugs(array &$scraped_data)
    {
        $datas = [
            [
                'Forestier',
                'https://api.waklaboratory.fr/images/job/bucheron.png',
                'collecte',
            ],
            [
                'Herboriste',
                'https://api.waklaboratory.fr/images/job/herboriste.png',
                'collecte',
            ],
            [
                'Mineur',
                'https://api.waklaboratory.fr/images/job/mineur.png',
                'collecte',
            ],
            [
                'Paysan',
                'https://api.waklaboratory.fr/images/job/paysan.png',
                'collecte',
            ],
            [
                'Pêcheur',
                'https://api.waklaboratory.fr/images/job/pecheur.png',
                'collecte',
            ],
            [
                'Trappeur',
                'https://api.waklaboratory.fr/images/job/trappeur.png',
                'collecte',
            ],
            [
                'Armurier',
                'https://api.waklaboratory.fr/images/job/armurier.png',
                'artisanat',
            ],
            [
                'Bijoutier',
                'https://api.waklaboratory.fr/images/job/bijoutier.png',
                'artisanat',
            ],
            [
                'Boulanger',
                'https://api.waklaboratory.fr/images/job/boulanger.png',
                'artisanat',
            ],
            [
                'Cuisinier',
                'https://api.waklaboratory.fr/images/job/cuisinier.png',
                'artisanat',
            ],
            [
                'Ébéniste',
                'https://api.waklaboratory.fr/images/job/ebeniste.png',
                'artisanat',
            ],
            [
                'Maitre d\'Armes',
                'https://api.waklaboratory.fr/images/job/maitre_arme.png',
                'artisanat',
            ],
            [
                'Maroquinier',
                'https://api.waklaboratory.fr/images/job/maroquinier.png',
                'artisanat',
            ],
            [
                'Tailleur',
                'https://api.waklaboratory.fr/images/job/tailleur.png',
                'artisanat',
            ],
        ];

        foreach ($datas as $data) {
            $obj = new Job();
            $obj->setName($data[0]);
            $obj->setIcon($data[1]);
            $obj->setType($data[2]);

            $scraped_data[$this->getKey()][$data[0]] = $obj;

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
