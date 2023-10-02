<?php

namespace App\Domain\ApartmentOffer;

use DateTimeImmutable;
use RuntimeException;

class ApartmentOfferException extends RuntimeException
{
    public static function notAvailableBetween(DateTimeImmutable $start, DateTimeImmutable $end): self
    {
        return new self(sprintf(
            'Apartment offer is not available between %s - %s',
            $start->format('Y-m-d'),
            $end->format('Y-m-d')
        ));
    }
}