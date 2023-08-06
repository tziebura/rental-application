<?php

namespace App\Domain\ApartmentOffer;

use RuntimeException;

class ApartmentAvailabilityException extends RuntimeException
{
    public static function startAfterEnd(): self
    {
        return new self('Start date of availability is after end date.');
    }
}