<?php

namespace App\Repository;

use App\Search\PagerFantaToPaginator;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\ReverseNested;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\FunctionScore;
use Elastica\Query\MatchQuery;
use Elastica\Query\Range;
use FOS\ElasticaBundle\Repository;

class SearchRepository extends Repository
{
    public function search(string $q = null, array $rarity = [], array $type = [], array $family = [], array $encyclopediaId = [], int $levelMin = null, int $levelMax = null, string $sort_field = null, string $sort_order = null, string $model = null, int $page = 1, int $item_per_page = 20)
    {
        $items = $this->findPaginated($this->getQuery($q, $rarity, $type, $family,  $encyclopediaId, $levelMin, $levelMax, $sort_field, $sort_order, $model));
        $items->setCurrentPage($page);
        $items->setMaxPerPage($item_per_page);

        return new PagerFantaToPaginator($items);
    }

    public function aggregate(string $model, string $q = null, array $rarity = [], array $type = [], array $family = [], int $levelMin = null, int $levelMax = null) {

        // Recherche
        $query = $this->getQuery(q : $q, rarity : $rarity, type : $type, family : $family, model : $model, levelMin : $levelMin, levelMax : $levelMax);
        $query->setSize(0);

        // Aggregation

        // Niveau
        $minLevel = new \Elastica\Aggregation\Min("minLevel");
        $minLevel->setField( !in_array($model, ["mob", "subzone"]) ? "level" : "levelMin");

        $query->addAggregation($minLevel);

        $maxLevel = new \Elastica\Aggregation\Max("maxLevel");
        $maxLevel->setField(!in_array($model, ["mob", "subzone"]) ? "level" : "levelMin");

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
        $type_terms->setSize(500);

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
        $family_terms->setSize(500);

        $family_object = new ReverseNested("family_object");

        $family_object->addAggregation((new Terms("family_name"))->setField("family.name"));

        $family_terms->addAggregation($family_object);

        $family->addAggregation($family_terms);

        $query->addAggregation($family);

        return $this->createPaginatorAdapter($query)->getAggregations();
    }

    private function getQuery(string $q = null, array $rarity = [], array $type = [], array $family = [], array $encyclopediaId = [], int $levelMin = null, int $levelMax = null, string $sort_field = null, string $sort_order = null, string $model = null) : Query {
        $query = new Query();

        $boolQuery = new BoolQuery();

        // Recherche

        // Nom
        if($q)
            $boolQuery->addMust(new MatchQuery("name", $q));
        else {
            $seed = time() . rand(10000, 20000);

            $random_score = new FunctionScore();
            $random_score->setRandomScore($seed);

            $boolQuery->addShould($random_score);
        }

        // Niveau
        if($levelMin || $levelMax) {
            $rangeQuery = new Range();

            $params = [];

            if($levelMin) {
                $params['gte'] = $levelMin;
            }

            if($levelMax) {
                $params['lte'] = $levelMax;
            }

            $rangeQuery->addField(in_array($model, ["mob", "subzone"]) ? 'levelMin' : 'level', $params);

            $boolQuery->addMust($rangeQuery);
        }

        // Rareté
        if(!empty($rarity)) {
            $rarityQuery = new BoolQuery();

            foreach($rarity as $r) {
                $rarityQuery->addShould(new MatchQuery("rarity.value", $r));
            }

            $boolQuery->addMust($rarityQuery);
        }

        // Type
        if(!empty($type)) {
            $typeQuery = new BoolQuery();

            foreach($type as $t) {
                $typeQuery->addShould(new MatchQuery("type.slug", $t));
            }

            $boolQuery->addMust($typeQuery);
        }

        // Famille
        if(!empty($family)) {
            $familyQuery = new BoolQuery();

            foreach($family as $f) {
                $familyQuery->addShould(new MatchQuery("family.slug", $f));
            }

            $boolQuery->addMust($familyQuery);
        }

        // Encyclopedia Id
        if(!empty($encyclopediaId)) {
            $encyclopediaIdQuery = new BoolQuery();

            foreach($encyclopediaId as $i) {
                $encyclopediaIdQuery->addShould(new MatchQuery("encyclopediaId", $i));
            }

            $boolQuery->addMust($encyclopediaIdQuery);
        }

        // Fin recherche
        $query->setQuery($boolQuery);

        // Tri
        if(isset($sort_field) && isset($sort_order)) {

            if($sort_field === "level" && in_array($model, ["mob", "subzone"])) {
                $sort_field = "levelMin";
            }

            if($sort_field === "rarity") {
                $sort_field = "rarity.value";
            }

            $query->setSort([$sort_field => ['order' => $sort_order]]);
        }

        return $query;
    }
}
