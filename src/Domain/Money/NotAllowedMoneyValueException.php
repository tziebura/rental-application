<?php

namespace App\Domain\Money;

use RuntimeException;

class NotAllowedMoneyValueException extends RuntimeException
{
    public static function of(float $price): self
    {
        return new self(sprintf('Price %s is not greater than zero.', $price));
    }
}