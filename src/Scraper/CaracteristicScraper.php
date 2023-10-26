<?php

namespace App\Scraper;

use App\Entity\Caracteristic;

class CaracteristicScraper extends Scraper
{
    public function getUrl(): string
    {
        return '';
    }

    public function getKey(): string
    {
        return 'carac';
    }

    public function getEntity(array $data = [], array &$scraped_data = [])
    {
        return Caracteristic::class;
    }

    public function getLinkedEntities(): array
    {
        return [
            Caracteristic::class,
        ];
    }

    public function getName(): string
    {
        return 'Cararacteristic';
    }

    public function fetchAllSlugs(array &$scraped_data)
    {
        $datas = array(
            array('name' => 'PV','icon' => 'https://api.waklaboratory.fr/images/caracteristic/vie.png'),
            array('name' => 'Tacle','icon' => 'https://api.waklaboratory.fr/images/caracteristic/tacle.png'),
            array('name' => 'Esquive','icon' => 'https://api.waklaboratory.fr/images/caracteristic/esquive.png'),
            array('name' => 'Maîtrise Mêlée','icon' => 'https://api.waklaboratory.fr/images/caracteristic/maitrise_melee.png'),
            array('name' => 'Coup critique','icon' => 'https://api.waklaboratory.fr/images/caracteristic/coup_critique.png'),
            array('name' => 'Portée','icon' => 'https://api.waklaboratory.fr/images/caracteristic/po.png'),
            array('name' => 'Maîtrise Distance','icon' => 'https://api.waklaboratory.fr/images/caracteristic/maitrise_distance.png'),
            array('name' => 'PA','icon' => 'https://api.waklaboratory.fr/images/caracteristic/pa.png'),
            array('name' => 'Maîtrise Critique','icon' => 'https://api.waklaboratory.fr/images/caracteristic/maitrise_critique.png'),
            array('name' => 'Résistance Eau','icon' => 'https://api.waklaboratory.fr/images/caracteristic/res_eau.png'),
            array('name' => 'Résistance Air','icon' => 'https://api.waklaboratory.fr/images/caracteristic/res_air.png'),
            array('name' => 'Résistance Terre','icon' => 'https://api.waklaboratory.fr/images/caracteristic/res_terre.png'),
            array('name' => 'Maîtrise Berserk','icon' => 'https://api.waklaboratory.fr/images/caracteristic/maitrise_berserk.png'),
            array('name' => 'PW','icon' => 'https://api.waklaboratory.fr/images/caracteristic/pw.png'),
            array('name' => 'Parade','icon' => 'https://api.waklaboratory.fr/images/caracteristic/parade.png'),
            array('name' => 'Résistance Feu','icon' => 'https://api.waklaboratory.fr/images/caracteristic/res_feu.png'),
            array('name' => 'Résistance Critique','icon' => 'https://api.waklaboratory.fr/images/caracteristic/res_critique.png'),
            array('name' => 'Maîtrise Soin','icon' => 'https://api.waklaboratory.fr/images/caracteristic/maitrise_soin.png'),
            array('name' => 'PW max','icon' => 'https://api.waklaboratory.fr/images/caracteristic/pw.png'),
            array('name' => 'Résistance Dos','icon' => 'https://api.waklaboratory.fr/images/caracteristic/res_dos.png'),
            array('name' => 'Maîtrise Dos','icon' => 'https://api.waklaboratory.fr/images/caracteristic/maitrise_dos.png'),
            array('name' => 'PM','icon' => 'https://api.waklaboratory.fr/images/caracteristic/pm.png'),
            array('name' => 'PA max','icon' => 'https://api.waklaboratory.fr/images/caracteristic/pa.png'),
            array('name' => 'PM max','icon' => 'https://api.waklaboratory.fr/images/caracteristic/pm.png'),
            array('name' => 'Maîtrise Feu','icon' => 'https://api.waklaboratory.fr/images/caracteristic/feu.png'),
            array('name' => 'Points de Vie','icon' => 'https://api.waklaboratory.fr/images/caracteristic/vie.png'),
            array('name' => 'Maîtrise Eau','icon' => 'https://api.waklaboratory.fr/images/caracteristic/eau.png'),
            array('name' => 'Maîtrise Terre','icon' => 'https://api.waklaboratory.fr/images/caracteristic/terre.png'),
            array('name' => 'Maîtrise Air','icon' => 'https://api.waklaboratory.fr/images/caracteristic/air.png'),
            array('name' => 'Initiative','icon' => 'https://api.waklaboratory.fr/images/caracteristic/initiative.png'),
            array('name' => 'Prospection','icon' => 'https://api.waklaboratory.fr/images/caracteristic/prospection.png'),
            array('name' => 'Sagesse','icon' => 'https://api.waklaboratory.fr/images/caracteristic/sagesse.png'),
            array('name' => '0% des dégâts','icon' => 'https://api.waklaboratory.fr/images/caracteristic/dommage.png'),
            array('name' => '1% des dégâts','icon' => 'https://api.waklaboratory.fr/images/caracteristic/dommage.png'),
            array('name' => '2% des dégâts','icon' => 'https://api.waklaboratory.fr/images/caracteristic/dommage.png'),
            array('name' => '3% des dégâts','icon' => 'https://api.waklaboratory.fr/images/caracteristic/dommage.png'),
            array('name' => '4% des dégâts','icon' => 'https://api.waklaboratory.fr/images/caracteristic/dommage.png'),
            array('name' => '5% des dégâts','icon' => 'https://api.waklaboratory.fr/images/caracteristic/dommage.png'),
            array('name' => '6% des dégâts','icon' => 'https://api.waklaboratory.fr/images/caracteristic/dommage.png'),
            array('name' => '7% des dégâts','icon' => 'https://api.waklaboratory.fr/images/caracteristic/dommage.png'),
            array('name' => '8% des dégâts','icon' => 'https://api.waklaboratory.fr/images/caracteristic/dommage.png'),
            array('name' => '9% des dégâts','icon' => 'https://api.waklaboratory.fr/images/caracteristic/dommage.png'),
            array('name' => '10% des dégâts','icon' => 'https://api.waklaboratory.fr/images/caracteristic/dommage.png')
        );

        foreach ($datas as $data) {
            $obj = new Caracteristic();
            $obj->setName($data['name']);
            $obj->setIcon($data['icon']);

            $scraped_data['carac'][$data['name']] = $obj;

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
