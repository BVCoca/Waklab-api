<?php

namespace App\Command;

use App\Scraper\ArmorScraper;
use App\Scraper\JobScraper;
use App\Scraper\MobScraper;
use App\Scraper\RarityScraper;
use App\Scraper\ResourceScraper;
use App\Scraper\WeaponScraper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Doctrine\ORM\EntityManagerInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:scrap-wakfu')]
class Scraping extends Command {

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $output->writeln("<info>Start to scraping wakfu</info>");

        $scrapers = [
            'rarity' => new RarityScraper($this->entityManager, $output),
            'job' => new JobScraper($this->entityManager, $output),
            'mob' => new MobScraper($this->entityManager, $output),
            'resource' => new ResourceScraper($this->entityManager, $output),
            'weapon' => new WeaponScraper($this->entityManager, $output),
            'armor' => new ArmorScraper($this->entityManager, $output)
        ];

        // On nettoie les données
        foreach($scrapers as $scraper) {
            $scraper->clear();
        }

        // On stock les données déja scrappées, car on a besoin pour faire les liens
        $scraped_data = [];

        // On récolte tous les slugs
        foreach($scrapers as $scraper) {
            $scraper->fetchAllSlugs($scraped_data);
        }

        // On fait le scrapping
        foreach($scrapers as $scraper) {
            $scraper->scrap($scraped_data);
        }

        // Bilan du scraping
        $table = new Table($output);
        $table
            ->setHeaderTitle("End of scraping wakfu - Summary")
            ->setHeaders(['Nom', 'Nombre'])
            ->setRows(array_map(fn($type, $data) => [ucfirst($type), count($data)], array_keys($scraped_data), $scraped_data))
            ->setStyle('box')
            ->setColumnWidths([30, 30]);

        $table->render();

        return Command::SUCCESS;
    }
}