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

    /** @throws \Doctrine\ORM\NoResultException */
    public function getRecipeAndCheckSlug(int $id, string $slug): Recipe
    {
        return $this->recipeGetter($id, $slug);
    }

    /** @throws \Doctrine\ORM\NoResultException */
    public function getRecipe(int $id): Recipe
    {
        return $this->recipeGetter($id);
    }

    /** @throws \Doctrine\ORM\NoResultException */
    private function recipeGetter(int $id, string $slug = null): Recipe
    {
        try {
            $query = $this->createQueryBuilder('r')
                ->where('r.id = :id')
                ->setParameter('id', $id);

                if ($slug !== null) {
                    $query->andWhere('r.slug = :slug')
                        ->setParameter('slug', $slug);
                }

                return $query->getQuery()
                ->getSingleResult();
        } catch (NonUniqueResultException $e) {
            // this should never happen but
            throw new NoResultException();
        }
    }
}
