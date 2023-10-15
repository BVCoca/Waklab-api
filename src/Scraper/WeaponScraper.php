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

class WeaponScraper extends Scraper {

    public function getUrl(): string {
        return 'https://www.wakfu.com/fr/mmorpg/encyclopedie/armes';
    }

    public function getKey() : string {
        return 'weapon';
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
        return 'Weapon';
    }

    public function getEntityData(string $slug, array &$scraped_data = []) {
        $weapon = $scraped_data[$this->getKey()][$slug];

        $crawler = $this->client->request('GET', $this->getUrl() . $slug);

        // Nom et image
        $weapon->setName(substr($crawler->filter("title")->innerText(), 0 , strpos($crawler->filter("title")->innerText(), '-')));
        $weapon->setImageUrl($crawler->filter(".ak-encyclo-detail-illu > img.img-maxresponsive")->attr('src'));

        // Niveau
        preg_match('/\d+/i', $crawler->filter(".ak-encyclo-detail-level")->innerText(), $level_match);
        $weapon->setLevel($level_match[0] ?? 100);

        // Description
        $path_description = "div.col-sm-9 > div >div.ak-container.ak-panel > div.ak-panel-title + div.ak-panel-content";
        if($crawler->filter($path_description)->count() > 0) {
            $weapon->setDescription($crawler->filter($path_description)->innerText());
        }

        // Rareté
        preg_match('/\d+/i',$crawler->filter("div.ak-object-rarity > span > span")->attr('class'), $rarity_match);

        if(isset($scraped_data['rarity'][$rarity_match[0]])) {
            $weapon->setRarity($scraped_data['rarity'][$rarity_match[0]]);
        }

        // Type d'armes
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

            $weapon->setType($scraped_data['type_stuff'][$label_type]); 
        }

        // Cout en PA et portée
        $raw_data = $crawler->filter('.ak-container.ak-panel.ak-encyclo-object-infos')->text('');

        $weapon->setCostPa(intval(substr($raw_data, 0, 1)));
        $weapon->setRequiredPo(intval(substr($raw_data, 1)));

        // Lecture des blocs d'effet, effets critique et carac
        $crawler->filter("div.ak-container.ak-panel.no-padding")->each(function($node) use ($weapon, &$scraped_data) {
            switch(trim($node->children()->first()->text())) {
                case 'Effets':
                    $effect_raw = $node->filter('div.ak-title')->innerText();

                    if(preg_match('/(.*?) : (\d+)/i', $effect_raw, $effect_match))
                    {
                        $weapon->setEffectType($effect_match[1]);
                        $weapon->setEffectValue($effect_match[2]);
                    } else {
                        $weapon->setEffectType($effect_raw);
                    }

                    // Type de zone
                    $image = $node->filter('div.ak-aside');

                    if($image->count() > 0 && $image->children()->count() > 1) {
                        $src = $image->children()->last()->attr('src');
                        $weapon->setZoneType(str_replace(['https://static.ankama.com/wakfu/portal/game/element/', '.png'], '', $src));
                    }
                    break;
                case 'Effets critiques':
                    $effect_raw = $node->filter('div.ak-title')->innerText();

                    if(preg_match('/(.*?) : (\d+)/i', $effect_raw, $effect_match))
                    {
                        $weapon->setCriticalEffectValue($effect_match[2]);
                    }
                    break;
                case 'Caractéristiques':
                    $node->filter('div.ak-title')->each(function($node) use($weapon, &$scraped_data) {
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
            
                        $weapon_carac = new StuffCaracteristic();
                        $weapon_carac->setCaracteristic($scraped_data['carac'][$carac_data[1]]);
                        $weapon_carac->setStuff($weapon);
                        $weapon_carac->setValue(intval($carac_data[0]));

                        $this->entityManager->persist($weapon_carac);
                    });
                    break;
            }
        });

        // Drop de mobs
        $crawler->filter('div.ak-panel-stack div.ak-image > a[href*="encyclopedie/monstres/"]')->each(function($a) use($weapon, &$scraped_data) {
            $mob_slug = !str_ends_with($a->attr('href'), '-') ? substr($a->attr('href'), strrpos($a->attr('href'), '/')) : '';

            $value = floatval($a->ancestors()->first()->siblings()->last()->innerText());

            // Si le mob existe on crée la relation
            if(isset($scraped_data['mob'][$mob_slug])) {
                $drop = new StuffDrop();

                $drop->setStuff($weapon);
                $drop->setMob($scraped_data['mob'][$mob_slug]);
                $drop->setValue($value);

                $this->entityManager->persist($drop);
            }
        });

        // Recette de craft
        $recipes = $this->getRecipes($crawler, $scraped_data);

        foreach($recipes as $recipe) {
            $weapon->addRecipe($recipe);
            $scraped_data['weapon_recipe'][] = 1;
        }

        return $weapon;
    }
}