<?php

namespace App\Domain\HotelRoom;

class HotelRoomFactory
{
    public function create(string $hotelId, int $number, string $description, array $rooms): HotelRoom
    {
        $rooms = array_map(function ($size, $name) {
            return new Room($name, new SquareMeter($size));
        }, $rooms, array_keys($rooms));

        return new HotelRoom($hotelId, $number, $description, $rooms);
    }
}