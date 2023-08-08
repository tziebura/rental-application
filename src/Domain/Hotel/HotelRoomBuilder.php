<?php

namespace App\Domain\Hotel;

use App\Domain\Space\SquareMeter;
use stdClass;

class HotelRoomBuilder
{
    private stdClass $carry;

    private function __construct()
    {
        $this->reset();
    }

    public static function create(): self
    {
        return new self();
    }

    private function reset(): void
    {
        $this->carry = new stdClass();
    }

    public function withHotel(Hotel $hotel): self
    {
        $this->carry->hotel = $hotel;
        return $this;
    }

    public function withNumber(int $number): self
    {
        $this->carry->number = $number;
        return $this;
    }

    public function withDescription(string $description): self
    {
        $this->carry->description = $description;
        return $this;
    }

    public function withRooms(array $rooms): self
    {
        $this->carry->rooms = $rooms;
        return $this;
    }

    public function build(): HotelRoom
    {
        $rooms = array_map(function ($size, $name) {
            return new Room($name, new SquareMeter($size));
        }, $this->carry->rooms, array_keys($this->carry->rooms));

        $hotelRoom = new HotelRoom($this->carry->hotel, $this->carry->number, $this->carry->description, $rooms);
        $this->reset();
        return $hotelRoom;
    }

}