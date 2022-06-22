<?php

namespace App\Exceptions;

use App\AdminCodes;
use Exception;

class AdminBusinessException extends Exception
{
    /**
     * Create a new AdminBusinessException instance.
     *
     * @param  int  $code
     * @return \App\Exceptions\AdminBusinessException
     */
    public static function make(int $code): self
    {
        return new self(AdminCodes::MESSAGES[$code], $code);
    }
}
