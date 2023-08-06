<?php

namespace App\Domain\HotelRoomOffer;

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

    public function withHotelRoomId(string $hotelRoomId): self
    {
        $this->carry->hotelRoomId = $hotelRoomId;
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

    public function build(): HotelRoomOffer
    {
        $hotelRoomOffer = new HotelRoomOffer(
            $this->carry->hotelRoomId,
            new Money($this->carry->price),
            new HotelRoomAvailability($this->carry->start, $this->carry->end)
        );

        $this->carry = new stdClass();
        return $hotelRoomOffer;
    }
}