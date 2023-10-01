<?php

namespace App\Domain\RentalPlaceAvailability;

use DateTimeImmutable;
use RuntimeException;

class RentalPlaceAvailabilityException extends RuntimeException
{
    public static function startAfterEnd(DateTimeImmutable $start, DateTimeImmutable $end): self
    {
        return new self(sprintf(
            'Start date %s of availability is after end date %s.',
            $start->format('Y-m-d'),
            $end->format('Y-m-d'),
        ));
    }

    public static function startEarlierThanToday(DateTimeImmutable $start): self
    {
        return new self(sprintf(
            'Start date must be at least today, %s given.',
            $start->format('Y-m-d'),
        ));
    }
}