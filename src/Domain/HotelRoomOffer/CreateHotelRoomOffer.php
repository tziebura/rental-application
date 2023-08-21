<?php

namespace App\Domain\HotelRoomOffer;

use DateTimeImmutable;

class CreateHotelRoomOffer
{
    private int $hotelRoomNumber;
    private float $price;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    /**
     * @param int $hotelRoomNumber
     * @param float $price
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable $end
     */
    public function __construct(int $hotelRoomNumber, float $price, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->hotelRoomNumber = $hotelRoomNumber;
        $this->price = $price;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return int
     */
    public function getHotelRoomNumber(): int
    {
        return $this->hotelRoomNumber;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getEnd(): DateTimeImmutable
    {
        return $this->end;
    }
}