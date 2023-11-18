<?php

namespace App\Repository;

use App\Entity\Subzone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subzone>
 *
 * @method Subzone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subzone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subzone[]    findAll()
 * @method Subzone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubzoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subzone::class);
    }

//    /**
//     * @return Subzone[] Returns an array of Subzone objects
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

//    public function findOneBySomeField($value): ?Subzone
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
