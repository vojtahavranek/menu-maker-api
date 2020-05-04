<?php declare(strict_types=1);

namespace MenuMaker\Types;

use MenuMaker\Entity\Recipe;
use MenuMaker\Types\Crates\RecipeCrate;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class RecipeFactory
{
    /**
     * @Factory()
     */
    public function createRecipe(string $name, ?string $image, ?string $description): RecipeCrate
    {
        return new RecipeCrate($name, $description, $image);
    }

    public static function createRecipeFromCrate(RecipeCrate $crate): Recipe
    {
        return new Recipe($crate->getName(), $crate->getDescription(), $crate->getImage());
    }
}
