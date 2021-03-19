<?php

namespace App\Repository;

use App\Entity\Bd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bd|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bd|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bd[]    findAll()
 * @method Bd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bd::class);
    }

    /**
     * Requête qui me permet de récuperer les bd par rapport à un titre ou bout de titre en recherche
     * @return Bd[]
     */
    public function findWithSearchTitre(String $titreSearch)
    {
        $query = $this
            ->createQueryBuilder('b')
            ->select('b');

        if (!empty($titreSearch)) {
            $query = $query
                ->andWhere('b.titre LIKE :titre')
                ->setParameter('titre', "%{$titreSearch}%");
        }

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Bd[] Returns an array of Bd objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bd
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
