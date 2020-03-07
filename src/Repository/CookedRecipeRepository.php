<?php

namespace MenuMaker\Repository;

use MenuMaker\Entity\CookedRecipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CookedRecipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method CookedRecipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method CookedRecipe[]    findAll()
 * @method CookedRecipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CookedRecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CookedRecipe::class);
    }

    // /**
    //  * @return CookedRecipe[] Returns an array of CookedRecipe objects
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
    public function findOneBySomeField($value): ?CookedRecipe
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
