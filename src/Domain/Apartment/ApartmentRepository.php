<?php

namespace App\Domain\Apartment;

interface ApartmentRepository
{
    public function save(Apartment $apartment): void;
}