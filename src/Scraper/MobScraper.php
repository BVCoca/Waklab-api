<?php

namespace App\Scraper;

use App\Entity\Family;
use App\Entity\Mobs;

class MobScraper extends Scraper {

    private $families = []; 

    public function getUrl(): string {
        return 'https://www.wakfu.com/fr/mmorpg/encyclopedie/monstres';
    }

    public function getEntities() : array
    {
        return [
            Mobs::class,
            Family::class
        ];
    }

    public function getName() : string
    {
        return 'Mob';
    }

    public function getEntityData(string $slug, array $scraped_data) {
        $mob = new Mobs();

        $crawler = $this->client->request('GET', $this->getUrl() . $slug);
        $mob->setName(substr($crawler->filter("title")->innerText(), 0 , strpos($crawler->filter("title")->innerText(), '-')));
        $mob->setImageUrl($crawler->filter(".ak-encyclo-detail-illu.ak-encyclo-detail-illu-monster > img")->attr('data-src'));

        // Niveau
        preg_match_all('/\d+/i', $crawler->filter(".ak-encyclo-detail-level")->innerText(), $match);
        $mob->setLevelMin($match[0][0]);
        $mob->setLevelMax($match[0][1] ?? $match[0][0]);

        // Caractéristiques
        $crawler->filter(".ak-container.ak-content-list.ak-displaymode-col > .ak-list-element > .ak-main > .ak-main-content > .ak-content > .ak-title")->each(function($node) use ($mob) {
            // Tableau de gauche
            if($node->innerText()) {
                switch(trim($node->innerText(), ' :')) {
                    case "Points de vie" :
                        $mob->setHP(intval($node->children()->first()->innerText()));
                    break;
                    case "Points d'action" :
                        $mob->setActionPoints(intval($node->children()->first()->innerText()));
                    break;
                    case "PM" :
                        $mob->setMovementPoints(intval($node->children()->first()->innerText()));
                    break;
                    case "Initiative" :
                        $mob->setInitiative(intval($node->children()->first()->innerText()));
                    break;
                    case "Tacle" :
                        $mob->setTackle(intval($node->children()->first()->innerText()));
                    break;
                    case "Esquive" :
                        $mob->setDodge(intval($node->children()->first()->innerText()));
                    break;
                    case "Parade" :
                        $mob->setParry(intval($node->children()->first()->innerText()));
                    break;
                    case "Coup critique" :
                        $mob->setCriticalHit(intval($node->children()->first()->innerText()));
                    break;
                }
            }
        });

        // Boost
        $crawler->filter(".ak-icon-small.ak-boost")->each(function($node, $key) use ($mob) {
            $value = intval(trim($node->siblings()->innerText(), '%'));

            switch($key) {
                case 0:
                    $mob->setAttackWater($value);
                break;
                case 1:
                    $mob->setAttackEarth($value);
                break;
                case 2:
                    $mob->setAttackWind($value);
                break;
                case 3:
                    $mob->setAttackFire($value);
                break;
            }
        });

        // Résistance
        $crawler->filter(".ak-icon-small.ak-resist")->each(function($node, $key) use ($mob) {
            $value = intval(trim($node->siblings()->innerText(), '%'));

            switch($key) {
                case 0:
                    $mob->setResWater($value);
                break;
                case 1:
                    $mob->setResEarth($value);
                break;
                case 2:
                    $mob->setResWind($value);
                break;
                case 3:
                    $mob->setResFire($value);
                break;
            }
        });

        // Capturable
        $mob->setIsCapturable(strcmp($crawler->filter(".catchable > strong")->innerText(), 'Non') !== 0);

        // Famille de mob
        if(!empty($crawler->filter(".ak-encyclo-detail-type > span")->text(''))) {
            $family_label = $crawler->filter(".ak-encyclo-detail-type > span")->innerText();

             // Si la famille existe déja alors on set juste, sinon on crée la famille
            if(!isset($families[$family_label]))
            {
                $new_family = new Family();
                $new_family->setName($family_label);

                $this->entityManager->persist($new_family);

                $families[$family_label] = $new_family;
            }

            $mob->setFamily($families[$family_label]);
        }

        return $mob;
    }
}