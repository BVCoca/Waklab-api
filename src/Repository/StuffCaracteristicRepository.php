<?php

namespace App\Repository;

use App\Entity\StuffCaracteristic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StuffCaracteristic>
 *
 * @method StuffCaracteristic|null find($id, $lockMode = null, $lockVersion = null)
 * @method StuffCaracteristic|null findOneBy(array $criteria, array $orderBy = null)
 * @method StuffCaracteristic[]    findAll()
 * @method StuffCaracteristic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StuffCaracteristicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StuffCaracteristic::class);
    }

    //    /**
    //     * @return StuffCaracteristic[] Returns an array of StuffCaracteristic objects
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

    //    public function findOneBySomeField($value): ?StuffCaracteristic
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
