<?php

namespace App\Application\HotelRoom;

use App\Domain\HotelRoom\HotelRoomFactory;

class HotelRoomApplicationService
{
    public function add(
        string $hotelId, int $number, string $description, array $rooms
    ): void {
        $factory = new HotelRoomFactory();
        $hotelRoom = $factory->create(
            $hotelId, $number, $description, $rooms);
    }
}