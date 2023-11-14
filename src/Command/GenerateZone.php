<?php

namespace App\Command;

use App\Entity\Dungeon;
use App\Entity\Family;
use App\Entity\Resource;
use App\Entity\Subzone;
use App\Entity\Zone;
use App\Repository\DungeonRepository;
use App\Repository\FamilyRepository;
use App\Repository\ResourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:generate-zone')]
class GenerateZone extends Command
{
    private EntityManagerInterface $entityManager;
    private FamilyRepository $FamilyRepository;
    private DungeonRepository $DungeonRepository;
    private ResourceRepository $ResourceRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;

        $this->FamilyRepository = $this->entityManager->getRepository(Family::class);
        $this->DungeonRepository = $this->entityManager->getRepository(Dungeon::class);
        $this->ResourceRepository = $this->entityManager->getRepository(Resource::class);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->clear();

        $zones = $this->getZone();

        // Pour chaque zone
        foreach($zones['zones'] as $z) {
            try {
                $zone = new Zone();
                $zone->setName($z['name']);
                $zone->setImageUrl($z['image']);
                $zone->setLevelMin(intval($z['levelMin']));
                $zone->setLevelMax(intval($z['levelMax']));

                // Pour chaque sous zone
                foreach($z['subzones'] as $s) {
                    $subzone = new Subzone();
                    $subzone->setName($s['name']);
                    $subzone->setImageUrl($s['image']);
                    $subzone->setLevelMin(intval($s['levelMin']));
                    $subzone->setLevelMax(intval($s['levelMax']));

                    // Famille de mobs
                    foreach($s['mobsFamily'] as $f) {
                        $family = $this->FamilyRepository->findOneBy([
                            'name' => $f['name']
                        ]);

                        if(!isset($family)) {
                            throw new Exception(sprintf("Zone %s : Famille %s non trouvée", $subzone->getName(), $f['name']));
                        } else {
                            $subzone->addMob($family);
                        }
                    }

                    // Ajout des dungeons
                    foreach($s['dungeons'] as $d) {
                        $dungeon = $this->DungeonRepository->findOneBy([
                            'name' => $d['name']
                        ]);

                        if(!isset($dungeon)) {
                            $output->writeln((sprintf("Zone %s : Donjon %s non trouvée", $subzone->getName(), $d['name'])));
                        } else {
                            $subzone->addDungeon($dungeon);
                        }
                    }

                    $zone->addSubzone($subzone);
                    $this->entityManager->persist($subzone);
                }

                $this->entityManager->persist($zone);
                $this->entityManager->flush();
            } catch(Exception $e) {
                $output->writeln($e->getMessage());
            }
        }

        return Command::SUCCESS;
    }

    private function clear() {
        // On désactive et réactive les forein key checks
        $conn = $this->entityManager->getConnection();
        $conn->executeQuery('SET FOREIGN_KEY_CHECKS=0;');
        $conn->executeQuery('TRUNCATE subzone_resource;');
        $conn->executeQuery('TRUNCATE subzone_family;');

        $this->entityManager->createQuery(
            'DELETE FROM ' . Zone::class . ' e'
        )->execute();

        $this->entityManager->createQuery(
            'DELETE FROM ' . Subzone::class . ' e'
        )->execute();

         $conn->executeQuery('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getZone() : array {
        $jsonString = file_get_contents('src/Command/zones.json');
        $jsonData = json_decode($jsonString, true);

        return $jsonData;
    }
}
