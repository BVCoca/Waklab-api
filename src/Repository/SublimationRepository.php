<?php

namespace App\Repository;

use App\Entity\Sublimation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sublimation>
 *
 * @method Sublimation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sublimation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sublimation[]    findAll()
 * @method Sublimation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SublimationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sublimation::class);
    }

//    /**
//     * @return Sublimation[] Returns an array of Sublimation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sublimation
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
