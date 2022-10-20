<?php

namespace App\Query\HotelRoom;

interface HotelRoomReadModel
{
    public function findByHotel(string $hotelId): array;
}