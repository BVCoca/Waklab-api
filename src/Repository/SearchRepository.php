<?php

namespace App\Repository;

use App\Search\PagerFantaToPaginator;
use FOS\ElasticaBundle\Repository;

class SearchRepository extends Repository
{
    public function search(string $searchTerm, int $page = 1, int $item_per_page = 20)
    {
        if ($searchTerm) {
            $fieldQuery = new \Elastica\Query\MultiMatch();
            $fieldQuery->setQuery($searchTerm);
            $fieldQuery->setFields(['name^10', 'family.name^2', 'type.name']);

            $items = $this->findPaginated($fieldQuery);
            $items->setCurrentPage($page);
            $items->setMaxPerPage($item_per_page);

            return new PagerFantaToPaginator($items);
        }

        return null;
    }
}
