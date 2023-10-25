<?php

namespace App\Scraper;

use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\Resource;
use App\Entity\ResourceDrop;
use App\Entity\TypeResource;

class ResourceScraper extends Scraper
{
    public function getUrl(): string
    {
        return 'https://www.wakfu.com/fr/mmorpg/encyclopedie/ressources';
    }

    public function getKey(): string
    {
        return 'resource';
    }

    public function getEntity(array $data = [], array &$scraped_data = [])
    {
        $resource = new Resource();
        $resource->setName($data['name'] ?? 'Sans nom');
        $resource->setImageUrl($data['image']);
        $resource->setLevel($data['level'][0][0]);
        $resource->setRarity($scraped_data['rarity'][$data['rarity']]);

        if (!isset($scraped_data['type_resource'][$data['type']])) {
            $typeResource = new TypeResource();
            $typeResource->setName($data['type']);
            $typeResource->setIcon($data['type_icon']);

            $this->entityManager->persist($typeResource);

            $scraped_data['type_resource'][$data['type']] = $typeResource;
        }

        $resource->setType($scraped_data['type_resource'][$data['type']]);

        return $resource;
    }

    public function getLinkedEntities(): array
    {
        return [
            Resource::class,
            ResourceDrop::class,
            Recipe::class,
            RecipeIngredient::class,
        ];
    }

    public function getName(): string
    {
        return 'Resource';
    }

    public function getEntityData(string $slug, array &$scraped_data = [])
    {
        $resource = $scraped_data[$this->getKey()][$slug];

        $crawler = $this->client->request('GET', $this->getUrl().$slug);

        $resource->setImageUrl($crawler->filter('.ak-encyclo-detail-illu > img.img-maxresponsive')->attr('src'));

        // Description
        if ($crawler->filter('div.col-sm-9 > div > div.ak-container.ak-panel > div.ak-panel-content')->count() > 0) {
            $resource->setDescription($crawler->filter('div.col-sm-9 > div > div.ak-container.ak-panel > div.ak-panel-content')->innerText());
        }

        // Drop de mobs
        $crawler->filter('div.ak-panel-stack div.ak-image > a[href*="encyclopedie/monstres/"]')->each(function ($a) use ($resource, $scraped_data) {
            $mob_slug = !str_ends_with($a->attr('href'), '-') ? substr($a->attr('href'), strrpos($a->attr('href'), '/')) : '';

            preg_match('/\d+/i', $a->ancestors()->first()->siblings()->last()->innerText(), $drop_match);
            $value = floatval($drop_match[0]);

            // Si le mob existe on crée la relation
            if (isset($scraped_data['mob'][$mob_slug])) {
                $drop = new ResourceDrop();

                $drop->setResource($resource);
                $drop->setMob($scraped_data['mob'][$mob_slug]);
                $drop->setValue($value);

                $this->entityManager->persist($drop);
            }
        });

        // Recette de craft
        $recipes = $this->getRecipes($crawler, $scraped_data);

        foreach ($recipes as $recipe) {
            $recipe->setResource($resource);
            $this->entityManager->persist($recipe);
            $scraped_data['resource_recipe'][] = 1;
        }

        return $resource;
    }
}
