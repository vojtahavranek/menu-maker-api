<?php declare(strict_types=1);

namespace MenuMaker\Entity;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 */
class Recipe
{
    /**
     * @Field()
     */
    public function getName(): string
    {
        return 'test name';
    }

    /**
     * @Field()
     */
    public function getDescription(): string
    {
        return 'test long long long description';
    }

    /**
     * @Field()
     */
    public function getRating(): float
    {
        return 4.77;
    }
}
