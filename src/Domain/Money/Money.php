<?php

namespace App\Domain\Money;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Money
{
    /**
     * @ORM\Column(type="float")
     */
    private float $value;

    private function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function of(float $price): self
    {
        if ($price <= 0) {
            throw NotAllowedMoneyValueException::of($price);
        }

        return new self($price);
    }

    public function getValue(): float
    {
        return $this->value;
    }
}