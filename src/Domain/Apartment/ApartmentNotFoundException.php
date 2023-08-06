<?php

namespace App\Domain\Apartment;

use RuntimeException;

class ApartmentNotFoundException extends RuntimeException
{
    public static function withId(string $apartmentId): self
    {
        return new self(sprintf('Apartment with ID %s does not exist', $apartmentId));
    }
}