<?php

namespace App\Command;

use App\Entity\Dungeon;
use App\Entity\Mobs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:method-scrap')]
class ScrapingMethod extends Command
{
    protected HttpBrowser $client;
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->client = new HttpBrowser();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->clear();

        // On parcours chaque page de DJ
        $donjons = $this->getDonjonsLink();

        $datas = [];


        foreach($donjons as $dj)
        {
            $dungeon = new Dungeon();

            $MobsRepository = $this->entityManager->getRepository(Mobs::class);

            $crawler = $this->client->request('GET', $dj['href']);

            $title_tag = $crawler->filter('title')->innerText();

            preg_match('/^(.*?) NIV.+?(\d+)/i', $title_tag, $match);

            $dungeon->setName(trim($match[1], '? -'));
            $dungeon->setLevel(intval($match[2]));

            // Encadré du haut de page pour récupérer le nombre de joueur et le nombre de salle
            $quote = '';

            if($crawler->filter('div > blockquote.wp-block-quote')->count() > 0)
                $quote = $crawler->filter('div > blockquote.wp-block-quote')->first()->text();


            // Nombre de joueur max
            preg_match('/(\d+) joueur/i', $quote, $player_match);

            if(isset($player_match[1])) {
                $dungeon->setMaxPlayer($player_match[1]);
            }

            // Nombre de salles
            preg_match('/(\d+) salle/i', $quote, $salle_match);

            if(isset($salle_match[1])) {
                $dungeon->setRoomCount($salle_match[1]);
            }

            $mobs = [];

            // Nom des mobs du DJ
            $crawler->filter('h3.wp-block-heading')->each(function($node) use(&$mobs) {
                if(str_starts_with($node->text(), '1.')) {
                    $raw_mob_name = $node->text();

                    preg_match('/–.(.*?)(?>$|.\()/', trim(str_ireplace(['(boss)', '(archimonstre)', '(invocation)', '(Mini boss)'],[''], $raw_mob_name)), $mob_match);

                    // Cas spécial pour les pious, larves et gugus
                    if($mob_match[1] === "Pious") {
                        $mob_match[1] = "Piou Bleu/Piou Vert/Piou Rouge/Piou Violet";
                    }

                    if($mob_match[1] === "Gugus") {
                        $mob_match[1] = "Gugu Bleu/Gugu Vert/Gugu Rouge/Gugu Violet";
                    }

                    if($mob_match[1] === "Larves") {
                        $mob_match[1] = "Larve Bleue/Larve Verte/Larve Orange/Larve Violette";
                    }

                    array_push($mobs,
                        ...explode(
                            "/",
                            str_replace(
                                ["&"],
                                ["/"],
                                trim($mob_match[1])
                            )
                        )
                    );
                }
            });

            foreach($mobs as $mob) {

                $mob = str_replace("’","'",trim(html_entity_decode($mob), " \t\n\r\0\x0B\xC2\xA0"));

                if(str_starts_with($mob , 'PARTIE'))
                {
                    continue;
                }

                $founded_mobs = $MobsRepository->findByName($mob);

                if(count($founded_mobs) > 1) {
                    // Pour la tour minéral on les prend tous
                    if(in_array($dungeon->getName(),["Donjon Tour Minérale"])) {
                        foreach($founded_mobs as $found_mob) {
                            $dungeon->addMob($found_mob);
                        }
                    } else {
                        $dungeon->addMob($founded_mobs[0]);
                    }
                } else if(count($founded_mobs) === 1) {
                    // J'en trouve un seul, c'est facile
                    $dungeon->addMob($founded_mobs[0]);
                } else if(count($founded_mobs) === 0) {
                    $output->writeln($dungeon->getName() . " : Aucun mob trouvé pour " . $mob);
                }
            }

            $this->entityManager->persist($dungeon);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }

    private function getDonjonsLink(): array {

        $crawler = $this->client->request('GET', 'https://methodwakfu.com/donjons/');

        $donjons = [];

        $crawler->filter('.wp-block-media-text__content > ul > li')->each(function($node) use (&$donjons) {

            // Récupération du nom et du href
            if($node->filter('a')->count() > 0) {
                $name = $node->filter('a')->innerText();
                $href = $node->filter('a')->attr('href');
            } else if($node->filter('strong')->count() > 0)
            {
                $name = $node->filter('strong')->innerText();
            }

            if($node->filter('img')->count(0)) {
                $type = $node->filter('img')->attr('title');
            }

            // Récupération du nombre de salle

            array_push($donjons,[
                'name' => $name ?? null,
                'href' => $href ?? null,
                'type' => $type ?? null
            ]);
        });

        // On enlève les donjons, qui n'ont pas de href
        $donjons = array_filter($donjons, fn($dj) => $dj['href'] !== null);

        return $donjons;
    }

     /**
     * Truncate la base de données
     */
    public function clear(): void {

        // On désactive et réactive les forein key checks
        $conn = $this->entityManager->getConnection();
        $conn->executeQuery('SET FOREIGN_KEY_CHECKS=0;');
        $conn->executeQuery('TRUNCATE dungeon_mobs;');

        $this->entityManager->createQuery(
            'DELETE FROM ' . Dungeon::class . ' e'
        )->execute();

        $conn->executeQuery('SET FOREIGN_KEY_CHECKS=1;');
    }
}
