<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use FOS\ElasticaBundle\Index\IndexManager;
use ApiPlatform\Metadata\Operation;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class FullTextFilter extends AbstractFilter
{
    private IndexManager $indexManager;

    public function __construct(IndexManager $indexManager, protected ManagerRegistry $managerRegistry, LoggerInterface $logger = null, protected ?array $properties = null, protected ?NameConverterInterface $nameConverter = null)
    {
        parent::__construct($managerRegistry, $logger, $properties, $nameConverter);
        $this->indexManager = $indexManager;
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'q' => [
                'property' => 'q',
                'type' => 'string',
                'required' => true
            ]
        ];
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void {
        
        $query = [
            '_source' => false, // you don't need the source document, just the ids
            'size' => 10000, // elasticsearch limit per page
            'query' => [
                'query_string' => [
                    'query' => $value,
                    'fields' => $this->properties['fields']
                ]
            ],
        ];

        $response = $this->indexManager->getIndex($this->properties['index'])->request('/_search', 'GET', $query);
        $ids = array_column($response->getData()['hits']['hits'] ?? [], '_id');

        if (empty($ids)) {
            $queryBuilder->andWhere('1 = 0'); // enforce empty result
            return;
        }

        // search for the ids in the query
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere("$rootAlias.id IN (:fulltext_search_filter_ids)");
        $queryBuilder->addSelect("FIELD($rootAlias.id, :fulltext_search_filter_ids) AS HIDDEN orderScoring");
        $queryBuilder->addOrderBy("orderScoring", "asc");
        $queryBuilder->setParameter('fulltext_search_filter_ids', $ids);
    }

}