<?php

namespace App\Domain\ApartmentOffer;

class Money
{
    private float $value;

    private function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function of(float $price): self
    {
        if ($price < 0) {
            throw NotAllowedMoneyValueException::of($price);
        }

        return new self($price);
    }
}