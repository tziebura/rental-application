<?php

namespace App\Domain\Apartment;

interface ApartmentRepository
{
    function save(Apartment $apartment): void;
}