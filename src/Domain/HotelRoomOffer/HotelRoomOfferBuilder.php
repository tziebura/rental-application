<?php

namespace App\Domain\HotelRoomOffer;

use App\Domain\Money\Money;
use DateTimeImmutable;
use stdClass;

class HotelRoomOfferBuilder
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

    public function withHotelRoomNumber(string $hotelRoomNumber): self
    {
        $this->carry->hotelRoomNumber = $hotelRoomNumber;
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

    public function withHotelId(string $hotelId): self
    {
        $this->carry->hotelId = $hotelId;
        return $this;
    }

    public function build(): HotelRoomOffer
    {
        $hotelRoomOffer = new HotelRoomOffer(
            $this->carry->hotelId,
            $this->carry->hotelRoomNumber,
            Money::of($this->carry->price),
            HotelRoomAvailability::of($this->carry->start, $this->carry->end)
        );

        $this->carry = new stdClass();
        return $hotelRoomOffer;
    }
}