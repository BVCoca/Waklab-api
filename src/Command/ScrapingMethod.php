<?php

namespace App\Command;

use App\Entity\Dungeon;
use App\Entity\Mobs;
use Doctrine\Common\Collections\Criteria;
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

        // Merci à Rudy pour les travaux
        $dungeons = [
            array(
                "level" => 36,
                "name" => "Donjon Équipage du Poulpe",
                "maxPlayer" => 6,
                "roomCount" => 2,
                "mobs" => array(
                    "flibustier d'asse",
                    "soeur de la côte",
                    "bataillard des mers",
                    "Pohl le poulpe"
                ),
            ),
            array(
                "level" => 36,
                "name" => "Donjon Morts-Brûlés",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "bwork brûlé",
                    "nimbrasier",
                    "défunéraire",
                    "Hark Saniss, Dernier géant"
                ),
            ),
            array(
                "level" => 51,
                "name" => "Tour des Miss Moches",
                "maxPlayer" => 3,
                "roomCount" => 1,
                "mobs" => array(
                    "Lela",
                    "Ydalipe",
                    "Erpel",
                    "Eenca"
                ),
            ),
            array(
                "level" => 51,
                "name" => "Donjon Marteaux-Aigris",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Marthos",
                    "Parrapuits",
                    "Bouclihash",
                    "Goël le golem"
                ),
            ),
            array(
                "level" => 66,
                "name" => "Temple du Grand Orrok",
                "maxPlayer" => 6,
                "roomCount" => 2,
                "mobs" => array(
                    "Krocky",
                    "Kroamagnon",
                    "Korbelle",
                    "Grand Orrok"
                ),
            ),
            array(
                "level" => 66,
                "name" => "Truchière Abandonnée",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Truchon",
                    "Truchemuche",
                    "Truche",
                    "Hongrue, Haute Truche"
                ),
            ),
            array(
                "level" => 66,
                "name" => "Domaine du Petit Groin",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Glaie",
                    "Marcassinet",
                    "Gligli agressif",
                    "Prespic",
                    "Gligli Royal",
                    "Sangria le Fruité"
                ),
            ),
            array(
                "level" => 66,
                "name" => "Palais du Tsu",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Moskito",
                    "Sangsuce Tsu Tsu",
                    "Gros Boo",
                    "Tsar Tsu Tsu"
                ),
            ),
            array(
                "level" => 81,
                "name" => "Le Misolée",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Gore-Gone",
                    "Gargrouilleur",
                    "Aplâtissier",
                    "Viktoria-France Kenstein"
                ),
            ),
            array(
                "level" => 81,
                "name" => "Académie Trool",
                "maxPlayer" => 3,
                "roomCount" => 2,
                "mobs" => array(
                    "Rey Mystroolrio",
                    "The Undertroolker",
                    "Troolk Hoogan",
                    "El Pochito"
                ),
            ),
            array(
                "level" => 81,
                "name" => "Le Hammamamoule",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "El Moulstacho",
                    "Ver Moulé Immature",
                    "Ver Moulé Vorace",
                    "Requin Moularteau",
                    "Bilbymoule Nacrée"
                ),
            ),
            array(
                "level" => 81,
                "name" => "Caverne des Slekymoses",
                "maxPlayer" => 6,
                "roomCount" => 2,
                "mobs" => array(
                    "Crasslek",
                    "Magislek",
                    "Long Brick",
                    "Reine Slek"
                ),
            ),
            array(
                "level" => 81,
                "name" => "Nécropoil de Morbax",
                "maxPlayer" => 6,
                "roomCount" => 4,
                "mobs" => array(
                    "Chafer lancier de la mine",
                    "Chafer archer de la mine",
                    "Chafer Elite de la mine",
                    "Chafer Elite",
                    "Chafer Lancier",
                    "Chafer Archer",
                    "Chaferfu, roi Chafer"
                ),
            ),
            array(
                "level" => 96,
                "name" => "Repaire des Super-Vilains",
                "maxPlayer" => 3,
                "roomCount" => 2,
                "mobs" => array(
                    "Lardevil",
                    "Kralaman",
                    "Mégathon",
                    "Merkator"
                ),
            ),
            array(
                "level" => 96,
                "name" => "L’Arène Dansante",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Bilbyboy Framboise",
                    "Bilbyboy Fraise",
                    "Bilbygirl Menthe",
                    "Bilbygirl Citron",
                    "Bilby Queen"
                ),
            ),
            array(
                "level" => 96,
                "name" => "Le Glaglacier Cornu",
                "maxPlayer" => 6,
                "roomCount" => 2,
                "mobs" => array(
                    "Glaglawi",
                    "Glagla Cornegivre",
                    "Glagla Froidacier",
                    "Lady Glagla"
                ),
            ),
            array(
                "level" => 96,
                "name" => "Donjon Gelée",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Gelée Framboise",
                    "Gelée Menthe",
                    "Gelée Fraise",
                    "Gelée Citron",
                    "Empereur Gelax"
                ),
            ),
            array(
                "level" => 96,
                "name" => "Chuchobase",
                "maxPlayer" => 6,
                "roomCount" => 4,
                "mobs" => array(
                    "Chuchoteurs Arbalétriers",
                    "Chuchoteurs Fantassins",
                    "Chuchoteur Porte-Etendard",
                    "Craqueboule Chuchoté",
                    "Craqueleur Chuchoté",
                    "Grand Craqueleur Chuchoté",
                    "Maître Chuchoku"
                ),
            ),
            array(
                "level" => 111,
                "name" => "Le Pot d’Hagën Glass",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Craktite",
                    "Crakmite",
                    "Crakeurn Polaire",
                    "Crarte d’Or",
                    "Hagën-Glass"
                ),
            ),
            array(
                "level" => 111,
                "name" => "Aile de l’Ambassadrice",
                "maxPlayer" => 6,
                "roomCount" => 2,
                "mobs" => array(
                    "Archer du Nord",
                    "Garde du Nord",
                    "Brute du Nord",
                    "Traqueur du Nord",
                    "Kya, Missiz Frizz"
                ),
            ),
            array(
                "level" => 111,
                "name" => "Caverne Smarrante",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Smarmot",
                    "Smare Couatique",
                    "Gros Smare",
                    "Smarillion"
                ),
            ),
            array(
                "level" => 111,
                "name" => "La Pichine",
                "maxPlayer" => 6,
                "roomCount" => 4,
                "mobs" => array(
                    "Don Rascaillès",
                    "Sergent Poiscaille",
                    "Bernardo dé la Carpett",
                    "Zespadon",
                    "Zespadon Noir"
                ),
            ),
            array(
                "level" => 126,
                "name" => "Temple de l’Empeleul Lenald",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Mini Lenald",
                    "Lenald",
                    "Vieux Lenald",
                    "Fatt Lenald",
                    "Empeleul Lenald"
                ),
            ),
            array(
                "level" => 126,
                "name" => "Donjon Noirespore",
                "maxPlayer" => 6,
                "roomCount" => 2,
                "mobs" => array(
                    "Cépolourpode Ecrasant",
                    "Amionite Bicéphaloïde",
                    "Haploïde Sporulateur",
                    "Coulemellanche Sauteur",
                    "Telob le Champmane"
                ),
            ),
            array(
                "level" => 126,
                "name" => "Domaine de la Trouffe Salée",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Bébé Phorreur",
                    "Phorreur Domestique",
                    "Phorreur Entraîné",
                    "Fripon"
                ),
            ),
            array(
                "level" => 141,
                "name" => "Vignoble Ignoble",
                "maxPlayer" => 6,
                "roomCount" => 4,
                "mobs" => array(
                    "Serprieuse",
                    "Papy Crate",
                    "Mamie Lésime",
                    "Dranipoch",
                    "Grande Prêtresse Sydonia"
                ),
            ),
            array(
                "level" => 141,
                "name" => "Wesewve de Cawottes Abandonnée",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Wabbit Tados",
                    "Gwand Pa Zwombbit Bandé",
                    "Wo Zwombbit",
                    "Gwand Wabbit GM"
                ),
            ),
            array(
                "level" => 141,
                "name" => "Donjon Srambad",
                "maxPlayer" => 6,
                "roomCount" => 2,
                "mobs" => array(
                    "Bulldague Nain",
                    "Dresseur Longuelames",
                    "Apothicaire Macchabrant",
                    "Sramva",
                    "Venâme le Mangelombre"
                ),
            ),
            array(
                "level" => 141,
                "name" => "Donjon Enutrosor",
                "maxPlayer" => 6,
                "roomCount" => 2,
                "mobs" => array(
                    "Chevalancier",
                    "Malozaure",
                    "Enutroffre-Fort",
                    "Kamarachnide",
                    "Jamall'Auneth le Porte-clés"
                ),
            ),
            array(
                "level" => 156,
                "name" => "Roub’Bar",
                "maxPlayer" => 6,
                "roomCount" => 2,
                "mobs" => array(
                    "Roublard Tiste",
                    "Blar le roux",
                    "Roublard Alard Amasse",
                    "Brise-Tibias",
                    "Remington Smisse"
                ),
            ),
            array(
                "level" => 201,
                "name" => "Donjon Toundrasoirs",
                "maxPlayer" => 6,
                "roomCount" => 2,
                "mobs" => array(
                    "Tigris",
                    "Loupin",
                    "Renard Valo",
                    "Bufflamboyant"
                ),
            ),
            array(
                "level" => 201,
                "name" => "Donjon Cagnardeurs",
                "maxPlayer" => 6,
                "roomCount" => 2,
                "mobs" => array(
                    "Carabajoie",
                    "Tatourbillon",
                    "Castorbital",
                    "Scorpiétineur"
                ),
            ),
            array(
                "level" => 201,
                "name" => "Donjon Carapattes",
                "maxPlayer" => 6,
                "roomCount" => 2,
                "mobs" => array(
                    "Caméléhombre",
                    "Dragonorrhée",
                    "Torturbulent",
                    "Tortumulte"
                ),
            ),
            array(
                "level" => 216,
                "name" => "Donjon Steamers",
                "maxPlayer" => 6,
                "roomCount" => 4,
                "mobs" => array(
                    "Artilleur d'élite",
                    "Unité stasificatrice",
                    "Mékano",
                    "Sir Comte FLex"
                ),
            ),
            array(
                "level" => 216,
                "name" => "Donjon Poisseux Abyssaux",
                "maxPlayer" => 6,
                "roomCount" => 3,
                "mobs" => array(
                    "Petit-poisseux",
                    "Cogneur abyssal",
                    "Assassirène",
                    "Raeliss"
                ),
            ),
        ];

        // On parcours chaque page de DJ
        $donjons = $this->getDonjonsLink();

        $MobsRepository = $this->entityManager->getRepository(Mobs::class);

        foreach($donjons as $dj)
        {
            $dungeon = [];

            $crawler = $this->client->request('GET', $dj['href']);

            $title_tag = $crawler->filter('title')->innerText();

            preg_match('/^(.*?) NIV.+?(\d+)/i', $title_tag, $match);

            $dungeon['name'] = trim($match[1], '? -');
            $output->writeln($dungeon['name']);
            $dungeon['level'] = intval($match[2]);

            // Encadré du haut de page pour récupérer le nombre de joueur et le nombre de salle
            $quote = '';

            if($crawler->filter('div > blockquote.wp-block-quote')->count() > 0)
                $quote = $crawler->filter('div > blockquote.wp-block-quote')->first()->text();


            // Nombre de joueur max
            preg_match('/(\d+) joueur/i', $quote, $player_match);

            if(isset($player_match[1])) {
                $dungeon['maxPlayer'] = $player_match[1];
            }

            // Nombre de salles
            preg_match('/(\d+) salle/i', $quote, $salle_match);

            if(isset($salle_match[1])) {
                $dungeon['roomCount'] = $salle_match[1];
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

                    // Cas des Bouftou Noir et Blanc
                    if(in_array($mob_match[1], ['Bouftou Blanc', 'Bouftou Noir']))
                    {
                        $mob_match[1] = str_replace('Bouftou', 'Boufton', $mob_match[1]);
                    }

                    // Cas du Blopgang
                    if($mob_match[1] === "Blopang Amadeus Mozart") {
                        $mob_match[1] = "Blopgang Amadeus Blopzart";
                    }

                     // Cas des rats imbibé
                    if($mob_match[1] === "Ratchitik Imbibé") {
                        $mob_match[1] = "Ratchitik";
                    }

                    if(str_ends_with($mob_match[1],"Os Imbibé")) {
                        $mob_match[1] = "Rat'Os";
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

            $dungeon['mobs'] = $mobs;

            $dungeons[] = $dungeon;
        }

        foreach($dungeons as $data) {
            $dungeon = new Dungeon();

            $dungeon->setName($data['name']);
            $dungeon->setLevel($data['level']);

            if(isset($data['maxPlayer'])) {
                $dungeon->setMaxPlayer($data['maxPlayer']);
            }

            if(isset($data['roomCount'])) {
                $dungeon->setRoomCount($data['roomCount']);
            }

            foreach($data['mobs'] as $mob) {

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

            $orderBy = (Criteria::create())->orderBy([
                'levelMax' => Criteria::DESC,
            ]);

            // On set le boss du dj, on prend le plus HL, et on l'enlève de la liste des movs
            if($dungeon->getMobs()->matching($orderBy)->first()) {
                $boss = $dungeon->getMobs()->matching($orderBy)->first();
                $dungeon->setBoss($boss);
                $dungeon->setImageUrl($boss->getImageUrl());
                $dungeon->removeMob($boss);
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
