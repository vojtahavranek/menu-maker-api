<?php declare(strict_types=1);

namespace MenuMaker\Exception;

use Throwable;

class ApiException extends \Exception
{
    public function __toString()
    {
        $message = [
            'code'    => $this->getCode(),
            'message' => $this->getMessage(),
            'trace'   => $this->getTraceAsString(),
        ];

        return json_encode($message);
    }
}
