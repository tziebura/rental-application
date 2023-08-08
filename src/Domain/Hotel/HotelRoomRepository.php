<?php

namespace App\Domain\Hotel;

interface HotelRoomRepository
{
    public function save(HotelRoom $hotelRoom): void;

    public function findById(string $id): ?HotelRoom;

    public function existsById(string $id): bool;
}