<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:subli-scrap')]
class ScrapingSubli extends Command
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
        $crawler = $this->client->request('GET', 'https://methodwakfu.com/optimisation/enchantement/');

        $sublis = [];

        $crawler->filter("#tablepress-enchant_sublis > tbody > tr")->each(function($node) use (&$sublis) {

            // Nom de la subli
            $name = $node->filter(".column-2 > a")->text();

            // Couleur des chasses
            $first_chasse = $node->filter(".column-1 > img:first-child")->attr('alt');
            $seconde_chasse = $node->filter(".column-1 > img:nth-child(2)")->attr('alt');
            $third_chasse = $node->filter(".column-1 > img:nth-child(3)")->attr('alt');

            // Effet
            $effect = str_replace(['<td class="column-3">','</td>'], [""], $node->filter(".column-3")->outerHtml());

            $sublis[] = [
                'name' => $name,
                'first_chasse' => $first_chasse ?? '',
                'seconde_chasse' => $seconde_chasse ?? '',
                'third_chasse' => $third_chasse ?? '',
                'effect' => $effect
            ];
        });



        return Command::SUCCESS;
    }

}
