<?php declare(strict_types=1);

namespace MenuMaker\Controller\Exception;

use MenuMaker\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthFailureException extends ApiException
{
    public function __construct($message = 'Authorization failed!', $code = Response::HTTP_UNAUTHORIZED, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
