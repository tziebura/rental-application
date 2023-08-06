<?php

namespace App\Domain\ApartmentOffer;

use RuntimeException;

class NotAllowedMoneyValueException extends RuntimeException
{
    public static function of(float $price): self
    {
        return new self(sprintf('Price %s is lower than zero.', $price));
    }
}