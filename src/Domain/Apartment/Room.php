<?php

namespace App\Domain\Apartment;

class Room
{
    private string $name;
    private SquareMeter $squareMeter;

    public function __construct(string $name, SquareMeter $squareMeter)
    {
        $this->name = $name;
        $this->squareMeter = $squareMeter;
    }
}