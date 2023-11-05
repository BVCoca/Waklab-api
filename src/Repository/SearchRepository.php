<?php

namespace App\Repository;

use App\Search\PagerFantaToPaginator;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\ReverseNested;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use FOS\ElasticaBundle\Repository;

class SearchRepository extends Repository
{
    public function search(string $q = null, int $page = 1, int $item_per_page = 20)
    {
        if ($q) {
            $fieldQuery = new \Elastica\Query\MultiMatch();
            $fieldQuery->setQuery($q);
            $fieldQuery->setFields(['name']);

            $items = $this->findPaginated($fieldQuery);
            $items->setCurrentPage($page);
            $items->setMaxPerPage($item_per_page);

            return new PagerFantaToPaginator($items);
        }

        return null;
    }

    public function aggregate(string $model, string $q = null) {
        $query = new Query();
        $query->setSize(0);

        // Recherche
        $fieldQuery = new \Elastica\Query\MultiMatch();
        $fieldQuery->setQuery($q);
        $fieldQuery->setFields(['name']);

        $query->setQuery($fieldQuery);

        // Aggregation

        // Niveau
        $minLevel = new \Elastica\Aggregation\Min("minLevel");
        $minLevel->setField($model !== "mob" ? "level" : "levelMin");

        $query->addAggregation($minLevel);

        $maxLevel = new \Elastica\Aggregation\Max("maxLevel");
        $maxLevel->setField($model !== "mob" ? "level" : "levelMin");

        $query->addAggregation($maxLevel);

        // Rareté
        $rarity = new Nested("rarity", "rarity");

        $rarity_terms = new Terms("rarity_terms");
        $rarity_terms->setField("rarity.value");

        $rarity_object = new ReverseNested("rarity_object");

        $rarity_object->addAggregation((new Terms("rarity_name"))->setField("rarity.name"));
        $rarity_object->addAggregation((new Terms("rarity_icon"))->setField("rarity.icon"));

        $rarity_terms->addAggregation($rarity_object);

        $rarity->addAggregation($rarity_terms);

        $query->addAggregation($rarity);

        // Type
        $type = new Nested("type", "type");

        $type_terms = new Terms("type_terms");
        $type_terms->setField("type.slug");

        $type_object = new ReverseNested("type_object");

        $type_object->addAggregation((new Terms("type_name"))->setField("type.name"));
        $type_object->addAggregation((new Terms("type_icon"))->setField("type.icon"));

        $type_terms->addAggregation($type_object);

        $type->addAggregation($type_terms);

        $query->addAggregation($type);

        // Famille
        $family = new Nested("family", "family");

        $family_terms = new Terms("family_terms");
        $family_terms->setField("family.slug");

        $family_object = new ReverseNested("family_object");

        $family_object->addAggregation((new Terms("family_name"))->setField("family.name"));

        $family_terms->addAggregation($family_object);

        $family->addAggregation($family_terms);

        $query->addAggregation($family);

        return $this->createPaginatorAdapter($query)->getAggregations();
    }
}
