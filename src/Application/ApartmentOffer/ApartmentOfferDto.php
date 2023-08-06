<?php

namespace App\Application\ApartmentOffer;

use DateTimeImmutable;

class ApartmentOfferDto
{
    private string $apartmentId;
    private float $price;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    public function __construct(string $apartmentId, float $price, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->apartmentId = $apartmentId;
        $this->price = $price;
        $this->start = $start;
        $this->end = $end;
    }

    public function getApartmentId(): string
    {
        return $this->apartmentId;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): DateTimeImmutable
    {
        return $this->end;
    }
}