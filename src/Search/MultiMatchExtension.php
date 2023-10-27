<?php

namespace App\Search;

use ApiPlatform\Elasticsearch\Extension\RequestBodySearchCollectionExtensionInterface;
use ApiPlatform\Metadata\Operation;

class MultiMatchExtension implements RequestBodySearchCollectionExtensionInterface
{
    public function applyToCollection(array $requestBody, string $resourceClass, Operation $operation = null, array $context = []): array
    {
        $query = [
            'query' => [
                'query_string' => [
                    'query' => $context['filters']['q'],
                    'fields' => $operation->getExtraProperties()['fields'],
                ],
            ],
        ];


        $sort_mapping = [
            'rarity' => 'rarity.value',
            'level' => 'level'
        ];

        // Tri
        if(isset($context['filters']['sort_field']) && isset($operation->getExtraProperties()['sort_mapping'])) {
            $query['sort'] = [
                $operation->getExtraProperties()['sort_mapping'][$context['filters']['sort_field']] => [
                    'order' => $context['filters']['sort_order'] ?? 'asc'
                ]
            ];
        }

        return $query;
    }
}
