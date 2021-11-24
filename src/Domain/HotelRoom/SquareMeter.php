<?php

namespace App\Domain\HotelRoom;

class SquareMeter
{
    private float $size;

    public function __construct(float $size)
    {
        $this->size = $size;
    }
}