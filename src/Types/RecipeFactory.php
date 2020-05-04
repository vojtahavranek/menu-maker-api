<?php declare(strict_types=1);

namespace MenuMaker\Types;

use MenuMaker\Entity\Recipe;
use MenuMaker\Exception\Recipe\EmptyRecipeNameException;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class RecipeFactory
{
    /** @Factory() */
    public function createRecipe(string $name, ?string $image, ?string $description): Input\Recipe
    {
        return new Input\Recipe($name, $description, $image);
    }

    /** @throws \MenuMaker\Exception\Recipe\EmptyRecipeNameException */
    public static function createRecipeFromInput(Input\Recipe $recipeInput): Recipe
    {
        if ($recipeInput->getName() === '') {
            throw new EmptyRecipeNameException();
        }
        
        return new Recipe($recipeInput->getName(), $recipeInput->getDescription(), $recipeInput->getImage());
    }
}
