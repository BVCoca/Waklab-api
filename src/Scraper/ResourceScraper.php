<?php

namespace App\Scraper;

use App\Entity\Rarity;
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

    public function getEntityData(string $slug, array $scraped_data) {
        $resource = new Resource();

        $crawler = $this->client->request('GET', $this->getUrl() . $slug);
        $resource->setName(substr($crawler->filter("title")->innerText(), 0 , strpos($crawler->filter("title")->innerText(), '-')));
        $resource->setImageUrl($crawler->filter(".ak-encyclo-detail-illu > img.img-maxresponsive")->attr('src'));

        return $resource;
    }
}