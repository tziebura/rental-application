<?php

namespace App\Domain\HotelRoomOffer;

class Money
{
    private float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

}