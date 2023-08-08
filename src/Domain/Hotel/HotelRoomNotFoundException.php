<?php

namespace App\Domain\Hotel;

use RuntimeException;

class HotelRoomNotFoundException extends RuntimeException
{
    public static function withNumber(string $hotelRoomNumber): self
    {
        return new self(sprintf('Hotel room with number %s does not exist', $hotelRoomNumber));
    }
}