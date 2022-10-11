<?php

namespace App\Domain\HotelRoom;

interface HotelRoomRepository
{
    public function save(HotelRoom $hotelRoom): void;

    public function findById(string $id): ?HotelRoom;
}