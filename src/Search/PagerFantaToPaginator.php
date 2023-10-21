<?php

namespace App\Search;

use ApiPlatform\State\Pagination\PaginatorInterface;
use Pagerfanta\Pagerfanta;

class PagerFantaToPaginator implements PaginatorInterface, \IteratorAggregate
{
    private Pagerfanta $pagerfanta;

    public function __construct(Pagerfanta $pagerfanta)
    {
        $this->pagerfanta = $pagerfanta;
    }

    public function count(): int
    {
        return $this->pagerfanta->getNbResults();
    }

    public function getLastPage(): float
    {
        return $this->pagerfanta->getNbPages();
    }

    public function getTotalItems(): float
    {
        return $this->pagerfanta->getNbResults();
    }

    public function getCurrentPage(): float
    {
        return $this->pagerfanta->getCurrentPage();
    }

    public function getItemsPerPage(): float
    {
        return $this->pagerfanta->getMaxPerPage();
    }

    /**
     * @return \Traversable<array-key, T>
     */
    public function getIterator(): \Traversable
    {
        return $this->pagerfanta->getIterator();
    }
}
