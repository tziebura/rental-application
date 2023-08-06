<?php

namespace App\Application\HotelRoomOffer;

use DateTimeImmutable;

class HotelRoomOfferDTO
{
    private string $hotelRoomId;
    private float $price;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    public function __construct(string $hotelRoomId, float $price, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->hotelRoomId = $hotelRoomId;
        $this->price = $price;
        $this->start = $start;
        $this->end = $end;
    }

    public function getHotelRoomId(): string
    {
        return $this->hotelRoomId;
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