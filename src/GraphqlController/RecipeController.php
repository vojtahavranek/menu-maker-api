<?php declare(strict_types=1);

namespace MenuMaker\GraphqlController;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use MenuMaker\Entity\Recipe;
use MenuMaker\Exception\Recipe\EmptyRecipeNameException;
use MenuMaker\Repository\RecipeRepository;
use MenuMaker\Types\Input\Recipe as RecipeInput;
use MenuMaker\Types\RecipeFactory;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLException;

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
     * @throws \TheCodingMachine\GraphQLite\Exceptions\GraphQLException
     */
    public function addRecipe(RecipeInput $recipe): bool
    {
        // TODO: make facade to handle
        try {
            $recipeEntity = RecipeFactory::createRecipeFromInput($recipe);
        } catch (EmptyRecipeNameException $e) {
            throw new GraphQLException($e->getMessage(), 500);
        }

        $this->entityManager->persist($recipeEntity);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @Mutation()
     * @throws \TheCodingMachine\GraphQLite\Exceptions\GraphQLException
     */
    public function cookRecipe(int $id): bool
    {
        try {
            // TODO: make facade to handle
            $recipe = $this->recipeRepository->getRecipe($id);
            $this->entityManager->persist($recipe->cook());
            $this->entityManager->flush();
        } catch (NoResultException $e) {
            throw new GraphQLException('Recipe note found');
        }

        return true;
    }

    /**
     * @Query()
     * @throws \TheCodingMachine\GraphQLite\Exceptions\GraphQLException
     */
    public function recipe(int $id, string $slug): ?Recipe
    {
        try {
            return $this->recipeRepository->getRecipeAndCheckSlug($id, $slug);
        } catch (NoResultException $e) {
            throw new GraphQLException('Recipe note found');
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
