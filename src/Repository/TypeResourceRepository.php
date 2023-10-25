<?php

namespace App\Repository;

use App\Entity\TypeResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeResource>
 *
 * @method TypeResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeResource[]    findAll()
 * @method TypeResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeResource::class);
    }

//    /**
//     * @return TypeResource[] Returns an array of TypeResource objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeResource
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
