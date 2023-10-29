<?php

namespace App\Search;

use ApiPlatform\Elasticsearch\Extension\RequestBodySearchCollectionExtensionInterface;
use ApiPlatform\Metadata\Operation;

class MultiMatchExtension implements RequestBodySearchCollectionExtensionInterface
{
    public function applyToCollection(array $requestBody, string $resourceClass, Operation $operation = null, array $context = []): array
    {
        // Recherche random si pas terme de recherche
        if(!empty($context['filters']['q'])) {
            $query = [
                'query' => [
                    'query_string' => [
                        'query' => $context['filters']['q'],
                        'fields' => $operation->getExtraProperties()['fields'],
                    ],
                ],
            ];

            // Tri
            if(isset($context['filters']['sort_field']) && isset($operation->getExtraProperties()['sort_mapping'])) {
                $query['sort'] = [
                    $operation->getExtraProperties()['sort_mapping'][$context['filters']['sort_field']] => [
                        'order' => $context['filters']['sort_order'] ?? 'asc'
                    ]
                ];
            }
        } else {
            $seed = time() . rand(10000, 20000);

            $query = [
                "query" => [
                    "function_score" => [
                      "random_score" => [
                        "seed" => $seed
                      ]
                    ]
                ]
            ];
        }

        return $query;
    }
}
