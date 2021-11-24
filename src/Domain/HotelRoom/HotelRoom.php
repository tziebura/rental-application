<?php

namespace App\Domain\HotelRoom;

class HotelRoom
{

    private string $hotelId;
    private int $number;
    private string $description;
    private array $rooms;

    public function __construct(string $hotelId, int $number, string $description, array $rooms)
    {
        $this->hotelId = $hotelId;
        $this->number = $number;
        $this->description = $description;
        $this->rooms = $rooms;
    }
}