<?php

namespace App\Query\HotelRoom;

class HotelRoom
{
    private int $id;
    private string $hotelId;
    private int $number;
    private string $description;
    private array $rooms;

    public function __construct(int $id, string $hotelId, int $number, string $description, array $rooms)
    {
        $this->id = $id;
        $this->hotelId = $hotelId;
        $this->number = $number;
        $this->description = $description;
        $this->rooms = $rooms;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['id'],
            $data['hotel_id'],
            (int) $data['number'],
            $data['description'],
            array_map(fn(array $room) => Room::fromArray($room), $data['rooms'])
        );
    }

    public function getId(): int
    {
        return $this->id;
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