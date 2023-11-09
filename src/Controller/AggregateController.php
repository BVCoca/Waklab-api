<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\SearchRepository;
use App\Search\MultiIndex;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AggregateController extends AbstractController
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

    #[Route(name:"api_aggregate", path:"/api/{model}/aggregate", methods:"GET", requirements: ['model' => 'mob|stuff|resource|dungeon'])]
    public function __invoke(Request $request, string $model)
    {

        if($model !== "all") {
            $this->multiIndex->setIndices([$model]);
        }

        $items = $this->searchRepository->aggregate(
            $model,
            $request->query->get('q'),
            array_filter(explode("|", $request->query->get('rarity') ?? "")),
            array_filter(explode("|", $request->query->get('type') ?? "")),
            array_filter(explode("|", $request->query->get('family') ?? ""))
        ) ?? [];

        // Transformation des aggrégations

        // Rareté
        $rarityBuckets = $items['rarity']['rarity_terms']['buckets'] ?? [];
        $rarityArray = [];

        foreach ($rarityBuckets as $bucket) {

            $rarityObjectAgg = $bucket['rarity_object'];
            $rarityValue = $bucket['key'];

            $iconValue = $rarityObjectAgg['rarity_icon']['buckets'][0]['key'];
            $nameValue = $rarityObjectAgg['rarity_name']['buckets'][0]['key'];

            $rarityObject = [
                'value' => $rarityValue,
                'icon' => $iconValue,
                'name' => $nameValue,
                'count' => $bucket['doc_count'],
                '@type' => 'Rarity'
            ];

            $rarityArray[] = $rarityObject;
        }

        $items['rarity'] = $rarityArray;

        // Type
        $typeBuckets = $items['type']['type_terms']['buckets'] ?? [];
        $typeArray = [];

        foreach ($typeBuckets as $bucket) {

            $typeObjectAgg = $bucket['type_object'];
            $typeValue = $bucket['key'];

            $iconValue = $typeObjectAgg['type_icon']['buckets'][0]['key'];
            $nameValue = $typeObjectAgg['type_name']['buckets'][0]['key'];

            $typeObject = [
                'value' => $typeValue,
                'icon' => $iconValue,
                'name' => $nameValue,
                'count' => $bucket['doc_count'],
                '@type' => 'Type'
            ];

            $typeArray[] = $typeObject;
        }

        $items['type'] = $typeArray;

        // Famille
        $familyBuckets = $items['family']['family_terms']['buckets'] ?? [];
        $familyArray = [];

        foreach ($familyBuckets as $bucket) {

            $familyObjectAgg = $bucket['family_object'];
            $familyValue = $bucket['key'];

            $nameValue = $familyObjectAgg['family_name']['buckets'][0]['key'];

            $familyObject = [
                'value' => $familyValue,
                'name' => $nameValue,
                'count' => $bucket['doc_count'],
                '@type' => 'Family'
            ];

            $familyArray[] = $familyObject;
        }

        $items['family'] = $familyArray;

        return new JsonResponse($items);
    }
}
