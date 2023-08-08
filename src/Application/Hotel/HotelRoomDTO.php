<?php

namespace App\Application\Hotel;

class HotelRoomDTO
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

    public function getHotelId(): string
    {
        return $this->hotelId;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getRooms(): array
    {
        return $this->rooms;
    }
}