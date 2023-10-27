<?php

namespace App\Scraper\Encyclo;

use App\Entity\Caracteristic;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\Stuff;
use App\Entity\StuffCaracteristic;
use App\Entity\StuffDrop;
use App\Entity\TypeStuff;

class EmblemScraper extends Scraper
{
    public function getUrl(): string
    {
        return 'https://www.wakfu.com/fr/mmorpg/encyclopedie/accessoires';
    }

    public function getKey(): string
    {
        return 'emblem';
    }

    public function getEntity(array $data = [], array &$scraped_data = [])
    {
        $emblem = new Stuff();
        $emblem->setName($data['name'] ?? 'Sans nom');
        $emblem->setImageUrl($data['image']);
        $emblem->setLevel($data['level'][0][0]);
        $emblem->setRarity($scraped_data['rarity'][$data['rarity']]);

        if (!isset($scraped_data['type_stuff'][$data['type']])) {
            $typeStuff = new TypeStuff();
            $typeStuff->setName($data['type']);
            $typeStuff->setIcon($data['type_icon']);

            $this->entityManager->persist($typeStuff);

            $scraped_data['type_stuff'][$data['type']] = $typeStuff;
        }

        $emblem->setType($scraped_data['type_stuff'][$data['type']]);

        return $emblem;
    }

    public function getLinkedEntities(): array
    {
        return [
            Stuff::class,
            TypeStuff::class,
            StuffCaracteristic::class,
            Caracteristic::class,
            StuffDrop::class,
            Recipe::class,
            RecipeIngredient::class,
        ];
    }

    public function getName(): string
    {
        return 'Emblem';
    }

    public function getEntityData(string $slug, array &$scraped_data = [])
    {
        $emblem = $scraped_data[$this->getKey()][$slug];

        $crawler = $this->client->request('GET', $this->getUrl().$slug);

        // Image
        $emblem->setImageUrl($crawler->filter('.ak-encyclo-detail-illu > img.img-maxresponsive')->attr('src'));

        // Description
        $path_description = 'div.col-sm-9 > div >div.ak-container.ak-panel > div.ak-panel-title + div.ak-panel-content';
        if ($crawler->filter($path_description)->count() > 0) {
            $emblem->setDescription($crawler->filter($path_description)->innerText());
        }

        // Lecture des blocs d'effet, effets critique et carac
        $crawler->filter('div.ak-container.ak-panel.no-padding')->each(function ($node) use ($emblem, &$scraped_data) {
            switch (trim($node->children()->first()->text())) {
                case 'Caractéristiques':
                    $node->filter('div.ak-title')->each(function ($node) use ($emblem, &$scraped_data) {
                        $carac_data = explode(' ', $node->innerText(), 2);

                        if (!isset($carac_data[1])) {
                            return;
                        }

                        if (!isset($scraped_data['carac'][$carac_data[1]])) {
                            $c = new Caracteristic();
                            $c->setName($carac_data[1]);

                            $this->entityManager->persist($c);

                            $scraped_data['carac'][$carac_data[1]] = $c;
                        }

                        $emblem_carac = new StuffCaracteristic();
                        $emblem_carac->setCaracteristic($scraped_data['carac'][$carac_data[1]]);
                        $emblem_carac->setStuff($emblem);
                        $emblem_carac->setValue(intval($carac_data[0]));

                        $this->entityManager->persist($emblem_carac);
                    });
                    break;
            }
        });

        // Drop de mobs
        $crawler->filter('div.ak-panel-stack div.ak-image > a[href*="encyclopedie/monstres/"]')->each(function ($a) use ($emblem, &$scraped_data) {
            $mob_slug = !str_ends_with($a->attr('href'), '-') ? substr($a->attr('href'), strrpos($a->attr('href'), '/')) : '';

            $value = floatval($a->ancestors()->first()->siblings()->last()->innerText());

            // Si le mob existe on crée la relation
            if (isset($scraped_data['mob'][$mob_slug])) {
                $drop = new StuffDrop();

                $drop->setStuff($emblem);
                $drop->setMob($scraped_data['mob'][$mob_slug]);
                $drop->setValue($value);

                $this->entityManager->persist($drop);
                $this->entityManager->flush();
            }
        });

        // Recette de craft
        $recipes = $this->getRecipes($crawler, $scraped_data);

        foreach ($recipes as $recipe) {
            $emblem->addRecipe($recipe);
            $this->entityManager->persist($recipe);
            $scraped_data['emblem_recipe'][] = 1;
        }

        return $emblem;
    }
}
