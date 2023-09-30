<?php

namespace App\Application\Apartment;

use Laminas\Code\Exception\RuntimeException;

class OwnerNotFoundException extends RuntimeException
{
    public static function withId(string $ownerId): self
    {
        return new self(sprintf('Owner with ID %s does not exist.', $ownerId));
    }
}