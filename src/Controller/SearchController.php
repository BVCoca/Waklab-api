<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\SearchRepository;
use App\Search\MultiIndex;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

    public function __invoke(Request $request)
    {
        if($request->query->get('model') !== "all") {
            $this->multiIndex->setIndices([$request->query->get('model')]);
        }

        $items = $this->searchRepository->search(
            $request->query->get('q'),
            array_filter(explode("|", $request->query->get('rarity') ?? "")),
            array_filter(explode("|", $request->query->get('type') ?? "")),
            array_filter(explode("|", $request->query->get('family') ?? "")),
            array_filter(explode("|", $request->query->get('encyclopedia_id') ?? "")),
            intval($request->query->get('levelMin')),
            intval($request->query->get('levelMax')),
            $request->query->get('sort_field'),
            $request->query->get('sort_order'),
            $request->query->get('model'),
            intval($request->query->get('page'))
        ) ?? [];

        return $items;
    }
}
