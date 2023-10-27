<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

class FullTextFilter extends AbstractFilter
{
    public function getDescription(string $resourceClass): array
    {
        return [
            'q' => [
                'property' => 'q',
                'type' => 'string',
                'required' => true,
            ],
            'page' => [
                'property' => 'page',
                'type' => 'integer',
                'required' => true,
            ],
            'sort_field' => [
                'property' => 'sort_field',
                'type' => 'string',
                'required' => false,
            ],
            'sort_order' => [
                'property' => 'sort_order',
                'type' => 'string',
                'required' => false,
            ]
        ];
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        // On fait rien, je fake juste pour l'extension
    }
}
