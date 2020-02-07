<?php declare(strict_types=1);

namespace MenuMaker\GraphqlController;

use MenuMaker\Entity\Recipe;
use TheCodingMachine\GraphQLite\Annotations\Query;

class RecipeController
{
    /**
     * @Query()
     */
    public function recipe(int $id): Recipe
    {
        return new Recipe();
    }

    /**
     * @Query()
     */
    public function user(): string
    {
        return 'user';
    }
}
