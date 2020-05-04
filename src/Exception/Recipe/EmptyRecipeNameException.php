<?php declare(strict_types=1);

namespace MenuMaker\Exception\Recipe;

use Throwable;

class EmptyRecipeNameException extends \Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('Missing recipe name!', $code, $previous);
    }
}
