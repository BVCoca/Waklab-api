<?php

namespace App\Repository;

use App\Entity\StuffDrop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StuffDrop>
 *
 * @method StuffDrop|null find($id, $lockMode = null, $lockVersion = null)
 * @method StuffDrop|null findOneBy(array $criteria, array $orderBy = null)
 * @method StuffDrop[]    findAll()
 * @method StuffDrop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StuffDropRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StuffDrop::class);
    }

    //    /**
    //     * @return StuffDrop[] Returns an array of StuffDrop objects
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

    //    public function findOneBySomeField($value): ?StuffDrop
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
