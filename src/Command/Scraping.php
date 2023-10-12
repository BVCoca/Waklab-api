<?php

namespace App\Command;

use App\Scraper\MobScraper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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

        $output->write(
            "=======================\n" .
            "Start to scraping wakfu\n" .
            "=======================\n"
        );

        $scrapers = [
           new MobScraper($this->entityManager, $output)
        ];

        // On nettoie les données
        foreach($scrapers as $scraper) {
            $scraper->clear();
        }

        // On fait le scrapping
        foreach($scrapers as $scraper) {
            $scraper->scrap();
        }

        $output->write(
            "=====================\n" .
            "End of scraping wakfu\n" .
            "=====================\n"
        );

        return Command::SUCCESS;
    }
}