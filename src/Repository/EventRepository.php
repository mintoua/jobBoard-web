<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllOrderByDate()
    {
        return $this->createQueryBuilder('q')
            ->orderBy('q.date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findMultiple($criteria){
        return $this->createQueryBuilder('p')
            ->where('p.nom LIKE  :criteria')
            ->orWhere('p.description LIKE :criteria')
            ->orWhere('p.adresse LIKE :criteria')
            ->orWhere('p.date LIKE :criteria')
            ->orWhere('p.nbrePlace LIKE :criteria')
            ->orWhere('p.prix LIKE :criteria')
            ->setParameter('criteria', '%'.$criteria.'%')
            ->getQuery()
            ->getResult();
    }

    public function orderByPrice(){
        return $this->createQueryBuilder('p')
            ->orderBy('p.prix', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function orderByTicket(){
        return $this->createQueryBuilder('p')
            ->orderBy('p.nbrePlace', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
