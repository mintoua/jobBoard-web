<?php

namespace App\Repository;

use App\Entity\DemandeRecrutement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\OffreEmploi;
use \Doctrine\DBAL\Connection;

/**
 * @method DemandeRecrutement|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeRecrutement|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeRecrutement[]    findAll()
 * @method DemandeRecrutement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeRecrutementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeRecrutement::class);
    }

    public function finddemande($off, $user)
    {

        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery('select m FROM App\Entity\DemandeRecrutement m WHERE m.offre =' . $off . ' and m.candidat =' . $user)
            ->getResult();
    }

    public function findOff($str)
    {

        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery('select m FROM App\Entity\OffreEmploi m WHERE m.id IN (' . $str . ')')
            ->getResult();
    }
    public function countOff($str)
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery('select count(m.id) FROM App\Entity\OffreEmploi m WHERE m.id IN (' . $str . ') ')

            ->getSingleScalarResult();
    }

    public function delap($id)
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery('delete from App\Entity\DemandeRecrutement m WHERE m.offre = ' . $id . 'and m.candidat = 2')
            ->execute();
    }

    /**
     * Returns number of "Annonces" per day
     * @return void 
     */
    public function countByDate()
    {
        $query = $this->getEntityManager()->createQuery("
            SELECT SUBSTRING(a.dateDebut, 1, 10) as dateAnnonces, COUNT(a) as count FROM App\Entity\DemandeRecrutement a GROUP BY dateAnnonces
        ");
        return $query->getResult();
    }

    // /**
    //  * @return DemandeRecrutement[] Returns an array of DemandeRecrutement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DemandeRecrutement
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
