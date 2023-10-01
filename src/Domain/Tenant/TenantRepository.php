<?php

namespace App\Domain\Tenant;

interface TenantRepository
{
    public function exists(string $id): bool;
}