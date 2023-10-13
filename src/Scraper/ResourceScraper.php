<?php

namespace App\Scraper;

use App\Entity\Resource;

class ResourceScraper extends Scraper {

    public function getUrl(): string {
        return 'https://www.wakfu.com/fr/mmorpg/encyclopedie/ressources';
    }

    public function getEntities() : array
    {
        return [
            Resource::class
        ];
    }

    public function getName() : string
    {
        return 'Resource';
    }

    public function getEntityData(string $slug, array $scraped_data = []) {
        $resource = new Resource();

        $crawler = $this->client->request('GET', $this->getUrl() . $slug);

        // Nom et image
        $resource->setName(substr($crawler->filter("title")->innerText(), 0 , strpos($crawler->filter("title")->innerText(), '-')));
        $resource->setImageUrl($crawler->filter(".ak-encyclo-detail-illu > img.img-maxresponsive")->attr('src'));
        
        // Niveau
        preg_match('/\d+/i', $crawler->filter(".ak-encyclo-detail-level")->innerText(), $level_match);
        $resource->setLevel($level_match[0]);

        // Description
        $resource->setDescription($crawler->filter("div.col-sm-9 > div > div.ak-container.ak-panel > div.ak-panel-content")->innerText());

        // Rareté
        preg_match('/\d+/i',$crawler->filter("div.ak-object-rarity > span > span")->attr('class'), $rarity_match);

        if(isset($scraped_data['rarity'][$rarity_match[0]])) {
            $resource->setRarity($scraped_data['rarity'][$rarity_match[0]]);
        }

        return $resource;
    }
}