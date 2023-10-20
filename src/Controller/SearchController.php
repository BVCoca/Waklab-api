<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\SearchRepository;
use App\Search\MultiIndex;
use Symfony\Component\HttpFoundation\Request;;

class SearchController extends AbstractController
{
    /**
     * @var SearchRepository
     */
    protected $searchRepository;

    /**
     * @var MultiIndex
     */
    protected $multiIndex;

    public function __construct(SearchRepository $searchRepository, MultiIndex $multiIndex)
    {
        $this->searchRepository = $searchRepository;
        $this->multiIndex = $multiIndex;
    }

    public function __invoke(Request $request) {
        $items = $this->searchRepository->search(
            $request->query->get('q'),
            intval($request->query->get('page'))
        ) ?? [];

        return $items;
    }
}
