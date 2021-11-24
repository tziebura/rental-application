<?php

namespace App\Domain\HotelRoom;

class Room
{
    private string $name;
    private SquareMeter $size;

    public function __construct(string $name, SquareMeter $size)
    {
        $this->name = $name;
        $this->size = $size;
    }
}