<?php

namespace App\Exceptions;

use Exception;

class ApiAccessErrorException extends Exception {

    private $extras;
    private $statusCode;
    private $reason;

    public function __construct(string $message = 'Unknown error', int $statusCode = 0, string $reason = '', array $extras = [])
    {
        parent::__construct($message);

        $this->statusCode = $statusCode;
        $this->extras   = $extras;
        $this->reason   = $reason;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getExtras()
    {
        return $this->extras;
    }

    public function getReason()
    {
        return $this->reason;
    }

}