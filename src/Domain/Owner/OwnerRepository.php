<?php

namespace App\Domain\Owner;

interface OwnerRepository
{
    public function exists(string $id): bool;
}