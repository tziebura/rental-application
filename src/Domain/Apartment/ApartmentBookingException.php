<?php

namespace App\Domain\Apartment;

use RuntimeException;

class ApartmentBookingException extends RuntimeException
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('There are accepted booking in given period.', $code, $previous);
    }
}