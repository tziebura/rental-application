<?php

namespace App\Domain\Space;

use RuntimeException;
use Throwable;

class SquareMeterException extends RuntimeException
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('Square meter cannot be lower or equal zero.', $code, $previous);
    }
}