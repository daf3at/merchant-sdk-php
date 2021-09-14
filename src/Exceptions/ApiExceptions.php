<?php

namespace Daf3at\Merchants\Exceptions;

use Throwable;

class ApiExceptions extends \Exception
{

    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = '')
    {
            $this->message  = $message;
            parent::__construct($message);
    }
}