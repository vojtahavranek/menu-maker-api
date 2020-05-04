<?php

namespace MenuMaker\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use MenuMaker\Entity\Recipe;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getRecipeAndCheckSlug(int $id, string $slug): Recipe
    {
        try {
            return $this->createQueryBuilder('r')
                ->where('r.id = :id AND r.slug = :slug')
                ->setParameters([
                    'id'   => $id,
                    'slug' => $slug
                ])
                ->getQuery()
                ->getSingleResult();
        } catch (NonUniqueResultException $e) {
            // this should never happen but
            throw new NoResultException();
        }
    }
}
