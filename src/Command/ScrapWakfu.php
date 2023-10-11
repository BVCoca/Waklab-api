<?php

namespace App\Command;

use App\Entity\Mobs;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Helper\ProgressBar;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:scrap-wakfu')]
class ScrapWakfu extends Command
{ 
    private HttpBrowser $client;
    private EntityManagerInterface $entityManager;

    CONST MOBS_URL = 'https://www.wakfu.com/fr/mmorpg/encyclopedie/monstres';

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->client = new HttpBrowser();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // On vide la base de données car on est des fou
        $output->write(
            "Start to scraping wakfu\n",
            "=======================\n"
        );

        $sectionPages = $output->section();
        $sectionMobs = $output->section();

        // Nombre de mobs et nombre de pages
        $crawler = $this->client->request('GET', self::MOBS_URL);

        $count_mobs = intval(trim($crawler->filter('.ak-list-info > strong')->innerText()));
        $count_pages = intval(trim($crawler->filter('.ak-pagination.hidden-xs > nav > ul > li:nth-child(8) > a')->innerText()));

        $progressBarPages = new ProgressBar($sectionPages, $count_pages);
        $progressBarMobs = new ProgressBar($sectionMobs, $count_mobs);

        $sectionPages->writeln('Scrap slug of all mobs');
        $progressBarPages->start();

        $mobs_slugs = [];

        // Récolte des liens pour chaque mob
        for($i = 1; $i <= $count_pages; $i++) {
            array_push($mobs_slugs, ...$this->scrap_mob_slugs($i));
            $progressBarPages->advance();

            sleep(1);
        }

        $progressBarPages->finish();
        $sectionPages->clear();
        $sectionMobs->writeln('Scrap data of all mobs');
        $progressBarMobs->start();

        // Passage sur chaque mob
        foreach($mobs_slugs as $key => $slug_mob) {

            if(!isset($slug_mob)) {
                continue;
            }

            $this->entityManager->persist($this->scrap_mob_data($slug_mob));

            $progressBarMobs->advance();
            sleep(1);
        }

        $this->entityManager->flush();

        $progressBarMobs->finish();

        $output->writeln("End of scraping wakfu");
        return Command::SUCCESS;
    }

    /**
     * Scrap du tableau
     * @param int page
     * @return array slugs
     */
    private function scrap_mob_slugs(int $page = 1) : array {
        $crawler = $this->client->request('GET', self::MOBS_URL . "?page=$page");
        $slugs = $crawler->filter('.ak-linker > a')->each(fn($a) => !str_ends_with($a->attr('href'), '-') ? substr($a->attr('href'), strrpos($a->attr('href'), '/')) : null);

        return $slugs;
    }

    /**
     * Scrap d'un mob
     * @param string slug du mob
     * @return array data
     */
    private function scrap_mob_data(string $slug) : Mobs {
        $mob = new Mobs();

        $crawler = $this->client->request('GET', self::MOBS_URL . $slug);
        $mob->setName(trim(substr($crawler->filter("title")->innerText(), 0 , strpos($crawler->filter("title")->innerText(), '-'))));
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

        return $mob;
    }
}