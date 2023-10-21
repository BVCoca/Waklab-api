<?php

namespace App\Command;

use App\Entity\Family;
use App\Entity\Job;
use App\Entity\Mobs;
use App\Entity\Resource;
use App\Entity\Stuff;
use App\Entity\TypeStuff;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:generate-slugs')]
class GenerateSlugs extends Command
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct();

        $this->doctrine = $doctrine;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Regenerate the slugs for entities.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $manager = $this->doctrine->getManager();

        foreach ([Family::class, Job::class, Mobs::class, Resource::class, Stuff::class, TypeStuff::class] as $class) {
            foreach ($manager->getRepository($class)->findAll() as $entity) {
                $entity->setSlug($this->slugify($entity->getName()));
                $manager->persist($entity);
            }

            $manager->flush();
            $manager->clear();

            $output->writeln("Slugs of \"$class\" updated.");
        }

        return Command::SUCCESS;
    }

    private function slugify(string $text, string $divider = '-'): string
    {
        $rules = <<<'RULES'
            :: Any-Latin;
            :: NFD;
            :: [:Nonspacing Mark:] Remove;
            :: NFC;
            :: [^-[:^Punctuation:]] Remove;
            :: Lower();
            [:^L:] { [-] > ;
            [-] } [:^L:] > ;
            [-[:Separator:]]+ > '-';
        RULES;

        return \Transliterator::createFromRules($rules)->transliterate($text);
    }
}
