<?php

namespace App\Repository;

use App\Entity\CandidateResume;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CandidateResume|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidateResume|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidateResume[]    findAll()
 * @method CandidateResume[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateResumeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CandidateResume::class);
    }

    // /**
    //  * @return CandidateResume[] Returns an array of CandidateResume objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CandidateResume
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
