<?php

namespace App\Scraper;

use App\Entity\Resource;
use App\Entity\ResourceDrop;

class ResourceScraper extends Scraper {

    public function getUrl(): string {
        return 'https://www.wakfu.com/fr/mmorpg/encyclopedie/ressources';
    }

    public function getKey() : string {
        return 'resource';
    }

    public function getEntity() {
        return new Resource();
    }

    public function getLinkedEntities() : array
    {
        return [
            Resource::class,
            ResourceDrop::class
        ];
    }

    public function getName() : string
    {
        return 'Resource';
    }

    public function getEntityData(string $slug, array &$scraped_data = []) {
        $resource = $scraped_data[$this->getKey()][$slug];

        $crawler = $this->client->request('GET', $this->getUrl() . $slug);

        // Nom et image
        $resource->setName(substr($crawler->filter("title")->innerText(), 0 , strpos($crawler->filter("title")->innerText(), '-')));
        $resource->setImageUrl($crawler->filter(".ak-encyclo-detail-illu > img.img-maxresponsive")->attr('src'));
        
        // Niveau
        preg_match('/\d+/i', $crawler->filter(".ak-encyclo-detail-level")->innerText(), $level_match);
        $resource->setLevel($level_match[0]);

        // Description
        if($crawler->filter("div.col-sm-9 > div > div.ak-container.ak-panel > div.ak-panel-content")->count() > 0) {
            $resource->setDescription($crawler->filter("div.col-sm-9 > div > div.ak-container.ak-panel > div.ak-panel-content")->innerText());
        }

        // Rareté
        preg_match('/\d+/i',$crawler->filter("div.ak-object-rarity > span > span")->attr('class'), $rarity_match);

        if(isset($scraped_data['rarity'][$rarity_match[0]])) {
            $resource->setRarity($scraped_data['rarity'][$rarity_match[0]]);
        }

        // Drop de mobs
        $crawler->filter('div.ak-panel-stack div.ak-image > a[href*="encyclopedie/monstres/"]')->each(function($a) use($resource, $scraped_data) {
            $mob_slug = !str_ends_with($a->attr('href'), '-') ? substr($a->attr('href'), strrpos($a->attr('href'), '/')) : '';

            preg_match('/\d+/i', $a->ancestors()->first()->siblings()->last()->innerText(), $drop_match);
            $value = intval($drop_match[0]);

            // Si le mob existe on crée la relation
            if(isset($scraped_data['mob'][$mob_slug])) {
                $drop = new ResourceDrop();

                $drop->setResource($resource);
                $drop->setMob($scraped_data['mob'][$mob_slug]);
                $drop->setValue($value);

                $this->entityManager->persist($drop);
                $this->entityManager->flush();
            }
        });

        // Recette de craft
        $recipes = $this->getRecipes($crawler, $scraped_data);

        foreach($recipes as $recipe) {
            $resource->addRecipe($recipe);
            $scraped_data['resource_recipe'][] = 1;
        }

        return $resource;
    }
}