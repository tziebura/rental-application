<?php

namespace App\Query\Apartment;

class Room
{
    private int $id;
    private string $name;
    private float $size;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): float
    {
        return $this->size;
    }
}