<?php

namespace App\Exceptions;

use Exception;

class ApiAccessErrorException extends Exception {

    private $extras;
    private $httpCode;

    public function __construct(string $message = 'Unknown error', int $httpCode = 0, array $extras = [])
    {
        parent::__construct($message);

        $this->httpCode = $httpCode;
        $this->extras = $extras;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }

    public function getExtras()
    {
        return $this->extras;
    }

}