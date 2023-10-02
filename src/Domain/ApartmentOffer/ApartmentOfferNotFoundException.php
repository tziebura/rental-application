<?php

namespace App\Domain\ApartmentOffer;

use RuntimeException;

class ApartmentOfferNotFoundException extends RuntimeException
{
    public static function forApartmentId(string $apartmentId): self
    {
        return new self(sprintf('Apartment offer not found for apartment %s', $apartmentId));
    }
}