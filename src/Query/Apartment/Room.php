<?php

namespace App\Query\Apartment;

class Room
{
    private int $id;
    private string $name;
    private float $size;

    public function __construct(int $id, string $name, float $size)
    {
        $this->id = $id;
        $this->name = $name;
        $this->size = $size;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['id'],
            $data['name'],
            (float) $data['square_meter_size']
        );
    }

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