<?php

namespace App\Repository;

use App\Entity\OffreEmploi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method OffreEmploi|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreEmploi|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreEmploi[]    findAll()
 * @method OffreEmploi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreEmploiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffreEmploi::class);
    }

    // /**
    //  * @return OffreEmploi[] Returns an array of OffreEmploi objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OffreEmploi
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function countj($filtre = null)
    {
        return $this->createQueryBuilder('u')
            ->select('count(u)')
            ->where('u.date_expiration > CURRENT_DATE() and u.titre LIKE :fil')
            ->setParameter('fil', '%' . $filtre . '%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countsearch($title, $location, $secteur)
    {
        return $this->createQueryBuilder('u')
            ->select('count(u)')
            ->innerJoin('u.categorie','c')
            ->where('c.titre LIKE :s')
            ->andWhere('u.titre LIKE :t and u.location LIKE :l and u.date_expiration > CURRENT_DATE()')
            ->setParameter(':t', '%' . $title . '%')
            ->setParameter(':l', '%' . $location . '%')
            ->setParameter(':s', '%' . $secteur . '%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function search($title, $location, $secteur)
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->innerJoin('u.categorie','c')
            ->where('c.titre LIKE :s')
            ->andWhere('u.titre LIKE :t and u.location LIKE :l and u.date_expiration > CURRENT_DATE()')
            ->setParameter(':t', '%' . $title . '%')
            ->setParameter(':l', '%' . $location . '%')
            ->setParameter(':s', '%' . $secteur . '%')
            ->getQuery()
            ->getResult();
    }

    public function getdonn($filtre = null)
    {
        $query = $this->createQueryBuilder('u')
            ->where(' u.date_expiration > CURRENT_DATE()');

        if ($filtre != null) {
            $query->andWhere('u.titre LIKE :t')
                ->setParameter(':t', '%' . $filtre . '%');
        }

        return $query->orderBy('u.date_debut', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
