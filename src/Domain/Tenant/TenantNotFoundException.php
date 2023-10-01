<?php

namespace App\Domain\Tenant;

use RuntimeException;

class TenantNotFoundException extends RuntimeException
{
    public static function withId(string $ownerId): self
    {
        return new self(sprintf('Tenant with ID %s does not exist.', $ownerId));
    }
}