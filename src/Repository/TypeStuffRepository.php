<?php

namespace App\Repository;

use App\Entity\TypeStuff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeStuff>
 *
 * @method TypeStuff|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeStuff|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeStuff[]    findAll()
 * @method TypeStuff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeStuffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeStuff::class);
    }

    //    /**
    //     * @return TypeStuff[] Returns an array of TypeStuff objects
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

    //    public function findOneBySomeField($value): ?TypeStuff
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
