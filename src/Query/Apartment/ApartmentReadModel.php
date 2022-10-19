<?php

namespace App\Query\Apartment;

interface ApartmentReadModel
{
    /**
     * @return Apartment[]
     */
    public function findAll(): array;
}