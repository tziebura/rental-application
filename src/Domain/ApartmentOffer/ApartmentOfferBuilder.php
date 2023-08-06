<?php

namespace App\Domain\ApartmentOffer;

use DateTimeImmutable;
use stdClass;

class ApartmentOfferBuilder
{
    private stdClass $carry;

    private function __construct()
    {
        $this->carry = new stdClass();
    }

    public static function create(): self
    {
        return new self();
    }

    public function withApartmentId(string $apartmentId): self
    {
        $this->carry->apartmentId = $apartmentId;
        return $this;
    }

    public function withPrice(float $price): self
    {
        $this->carry->price = $price;
        return $this;
    }

    public function withAvailability(DateTimeImmutable $start, DateTimeImmutable $end): self
    {
        $this->carry->start = $start;
        $this->carry->end = $end;
        return $this;
    }

    public function build(): ApartmentOffer
    {
        $offer = new ApartmentOffer(
            $this->carry->apartmentId,
            Money::of($this->carry->price),
            ApartmentAvailability::of($this->carry->start, $this->carry->end)
        );

        $this->carry = new stdClass();
        return $offer;
    }
}