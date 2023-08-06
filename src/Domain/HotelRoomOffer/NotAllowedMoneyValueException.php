<?php

namespace App\Domain\HotelRoomOffer;

use RuntimeException;

class NotAllowedMoneyValueException extends RuntimeException
{
    public static function of(float $price): self
    {
        return new self(sprintf('Price %s is lower than or equal to zero.', $price));
    }
}