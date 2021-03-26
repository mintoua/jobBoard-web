<?php

namespace App\Repository;

use App\Entity\ProductCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCart[]    findAll()
 * @method ProductCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCart::class);
    }

    // /**
    //  * @return ProductCart[] Returns an array of ProductCart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductCart
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
