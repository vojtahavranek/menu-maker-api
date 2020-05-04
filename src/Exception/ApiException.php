<?php declare(strict_types=1);

namespace MenuMaker\Exception;

class ApiException extends \Exception
{
    public function __toString()
    {
        $message = [
            'code'    => $this->getCode(),
            'message' => $this->getMessage(),
            'trace'   => $this->getTraceAsString(),
        ];

        return (string) json_encode($message);
    }
}
