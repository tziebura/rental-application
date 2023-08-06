<?php

namespace App\Domain\HotelRoomOffer;

class Money
{
    private float $value;

    private function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function of(float $price): self
    {
        if ($price <= 0) {
            throw new NotAllowedMoneyValueException(sprintf('Price %s is lower than or equal to zero.', $price));
        }

        return new self($price);
    }
}