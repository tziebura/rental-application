<?php

namespace App\Domain\ApartmentOffer;

class Money
{
    private float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }
}