<?php declare(strict_types=1);

namespace MenuMaker\GraphqlController;

use TheCodingMachine\GraphQLite\Annotations\Query;

class RecipeController
{
    /**
     * @Query()
     */
    public function recipe(int $id): string
    {
        return 'Thats my ' . $id . ' recipe';
    }
}
