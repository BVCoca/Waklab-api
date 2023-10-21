<?php

namespace App\Search;

use ApiPlatform\Elasticsearch\Extension\RequestBodySearchCollectionExtensionInterface;
use ApiPlatform\Metadata\Operation;

class MultiMatchExtension implements RequestBodySearchCollectionExtensionInterface
{
    public function applyToCollection(array $requestBody, string $resourceClass, Operation $operation = null, array $context = []): array
    {
        return [
            'query' => [
                'query_string' => [
                    'query' => $context['filters']['q'],
                    'fields' => $operation->getExtraProperties()['fields'],
                ],
            ],
        ];
    }
}
