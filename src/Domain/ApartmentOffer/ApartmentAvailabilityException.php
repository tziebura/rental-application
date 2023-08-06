<?php

namespace App\Domain\ApartmentOffer;

use DateTimeImmutable;
use RuntimeException;

class ApartmentAvailabilityException extends RuntimeException
{
    public static function startAfterEnd(DateTimeImmutable $start, DateTimeImmutable $end): self
    {
        return new self(sprintf(
            'Start date %s of availability is after end date %s.',
            $start->format('Y-m-d'),
            $end->format('Y-m-d'),
        ));
    }
}