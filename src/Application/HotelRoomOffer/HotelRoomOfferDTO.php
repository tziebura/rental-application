<?php

namespace App\Application\HotelRoomOffer;

use DateTimeImmutable;

class HotelRoomOfferDTO
{
    private string $hotelId;
    private int $hotelRoomNumber;
    private float $price;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    public function __construct(string $hotelId, int $hotelRoomNumber, float $price, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->hotelId = $hotelId;
        $this->hotelRoomNumber = $hotelRoomNumber;
        $this->price = $price;
        $this->start = $start;
        $this->end = $end;
    }

    public function getHotelId(): string
    {
        return $this->hotelId;
    }

    public function getHotelRoomNumber(): int
    {
        return $this->hotelRoomNumber;
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