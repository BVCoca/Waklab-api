<?php

namespace App\Repository;

use App\Entity\Mobs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mobs>
 *
 * @method Mobs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mobs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mobs[]    findAll()
 * @method Mobs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MobsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mobs::class);
    }

    public function findByName(string $value): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('LOWER(m.name) = LOWER(:val)')
            ->setParameter('val', $value)
            ->orderBy('m.levelMax', 'DESC')
            ->addOrderBy('m.family', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByNameLike(string $value): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('LOWER(m.name) LIKE LOWER(:val)')
            ->setParameter('val', "%" . $value . "%")
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Mobs[] Returns an array of Mobs objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Mobs
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
