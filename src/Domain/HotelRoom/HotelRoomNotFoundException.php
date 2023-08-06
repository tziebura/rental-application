<?php

namespace App\Domain\HotelRoom;

use RuntimeException;

class HotelRoomNotFoundException extends RuntimeException
{
    public static function withId(string $hotelRoomId): self
    {
        return new self(sprintf('Hotel room with ID %s does not exist', $hotelRoomId));
    }
}