<?php declare(strict_types=1);

namespace MenuMaker\GraphqlController;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use MenuMaker\Entity\Recipe;
use MenuMaker\Repository\RecipeRepository;
use MenuMaker\Types\Crates\RecipeCrate;
use MenuMaker\Types\RecipeFactory;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;

class RecipeController
{
    private $recipeRepository;
    private $entityManager;

    /**
     * RecipeController constructor.
     */
    public function __construct(RecipeRepository $recipeRepository, EntityManagerInterface $entityManager)
    {
        $this->recipeRepository = $recipeRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Mutation()
     */
    public function addRecipe(RecipeCrate $recipeCrate): bool
    {
        $recipe = RecipeFactory::createRecipeFromCrate($recipeCrate);
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @Mutation()
     * @Logged()
     */
    public function cookRecipe(int $id): bool
    {
        $recipe = $this->recipeRepository->find($id);

        return true;
    }

    /**
     * @Query()
     */
    public function recipe(int $id, string $slug): ?Recipe
    {
        try {
            return $this->recipeRepository->getRecipeAndCheckSlug($id, $slug);
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @Logged()
     * @Query()
     * @return Recipe[]
     */
    public function makemenu(): array
    {
        return $this->recipeRepository->findAll();
    }

    /**
     * @Query()
     */
    public function user(): string
    {
        return 'user';
    }
}
