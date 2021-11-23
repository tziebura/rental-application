<?php

namespace App\Domain\Apartment;

class SquareMeter
{
    private float $size;

    public function __construct(float $size)
    {
        $this->size = $size;
    }
}