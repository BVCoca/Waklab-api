<?php

namespace App\Scraper;

use App\Entity\Stuff;
use App\Entity\StuffDrop;
use App\Entity\TypeStuff;
use App\Entity\Caracteristic;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\StuffCaracteristic;
use Exception;

class ArmorScraper extends Scraper {

    public function getUrl(): string {
        return 'https://www.wakfu.com/fr/mmorpg/encyclopedie/armures';
    }

    public function getKey() : string {
        return 'armor';
    }

    public function getEntity() {
        return new Stuff();
    }

    public function getLinkedEntities() : array
    {
        return [
            Stuff::class,
            TypeStuff::class,
            StuffCaracteristic::class,
            Caracteristic::class,
            StuffDrop::class
        ];
    }

    public function getName() : string
    {
        return 'Armor';
    }

    public function getEntityData(string $slug, array &$scraped_data = []) {
        $armor = $scraped_data[$this->getKey()][$slug];

        $crawler = $this->client->request('GET', $this->getUrl() . $slug);

        // Nom et image
        $armor->setName(substr($crawler->filter("title")->innerText(), 0 , strpos($crawler->filter("title")->innerText(), '-')));
        $armor->setImageUrl($crawler->filter(".ak-encyclo-detail-illu > img.img-maxresponsive")->attr('src'));

        // Niveau
        preg_match('/\d+/i', $crawler->filter(".ak-encyclo-detail-level")->innerText(), $level_match);
        $armor->setLevel($level_match[0] ?? 100);

        // Description
        $path_description = "div.col-sm-9 > div >div.ak-container.ak-panel > div.ak-panel-title + div.ak-panel-content";
        if($crawler->filter($path_description)->count() > 0) {
            $armor->setDescription($crawler->filter($path_description)->innerText());
        }

        // Rareté
        preg_match('/\d+/i',$crawler->filter("div.ak-object-rarity > span > span")->attr('class'), $rarity_match);

        if(isset($scraped_data['rarity'][$rarity_match[0]])) {
            $armor->setRarity($scraped_data['rarity'][$rarity_match[0]]);
        }

        // Type d'armures
        $path_type = ".ak-encyclo-detail-type.col-xs-6 > span";
        if($crawler->filter($path_type)->count() === 1) {

            $label_type = $crawler->filter($path_type)->innerText();

            if(!isset($scraped_data['type_stuff'][$label_type])) {
                $typeStuff = new TypeStuff();
                $typeStuff->setName($label_type);
                $typeStuff->setIcon($crawler->filter($path_type)->children()->first()->attr('src'));

                $this->entityManager->persist($typeStuff);

                $scraped_data['type_stuff'][$label_type] = $typeStuff;
            }

            $armor->setType($scraped_data['type_stuff'][$label_type]); 
        }

        // Lecture des blocs d'effet, effets critique et carac
        $crawler->filter("div.ak-container.ak-panel.no-padding")->each(function($node) use ($armor, &$scraped_data) {
            switch(trim($node->children()->first()->text())) {
                case 'Caractéristiques':
                    $node->filter('div.ak-title')->each(function($node) use($armor, &$scraped_data) {
                        $carac_data = explode(" ", $node->innerText(), 2);

                        if(!isset($carac_data[1])) {
                            return;
                        }

                        if(!isset($scraped_data['carac'][$carac_data[1]])) {
                            $c = new Caracteristic();
                            $c->setName($carac_data[1]);
            
                            $this->entityManager->persist($c);
            
                            $scraped_data['carac'][$carac_data[1]] = $c;
                        }
            
                        $armor_carac = new StuffCaracteristic();
                        $armor_carac->setCaracteristic($scraped_data['carac'][$carac_data[1]]);
                        $armor_carac->setStuff($armor);
                        $armor_carac->setValue(intval($carac_data[0]));

                        $this->entityManager->persist($armor_carac);
                    });
                    break;
            }
        });

        // Drop de mobs
        $crawler->filter('div.ak-panel-stack div.ak-image > a[href*="encyclopedie/monstres/"]')->each(function($a) use($armor, &$scraped_data) {
            $mob_slug = !str_ends_with($a->attr('href'), '-') ? substr($a->attr('href'), strrpos($a->attr('href'), '/')) : '';

            $value = floatval($a->ancestors()->first()->siblings()->last()->innerText());

            // Si le mob existe on crée la relation
            if(isset($scraped_data['mob'][$mob_slug])) {
                $drop = new StuffDrop();

                $drop->setStuff($armor);
                $drop->setMob($scraped_data['mob'][$mob_slug]);
                $drop->setValue($value);

                $this->entityManager->persist($drop);
                $this->entityManager->flush();
            }
        });

        
        // Recette de craft
        $recipes = $this->getRecipes($crawler, $scraped_data);

        foreach($recipes as $recipe) {
            $armor->addRecipe($recipe);
            $scraped_data['armor_recipe'][] = 1;
        }

        return $armor;
    }
}