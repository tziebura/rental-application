<?php

namespace App\Domain\HotelRoom;

interface HotelRoomRepository
{
    public function save(HotelRoom $hotelRoom): void;
}