<?php

namespace App\Domain\Address;

use RuntimeException;

class AddressException extends RuntimeException
{
    public static function notExists(): self
    {
        return new self('Address does not exist');
    }
}