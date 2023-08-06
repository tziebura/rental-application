<?php

namespace App\Domain\HotelRoomOffer;

use DateTimeImmutable;

class HotelRoomAvailability
{
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->start = $start->setTime(0, 0);
        $this->end = $end->setTime(0, 0);
    }

}