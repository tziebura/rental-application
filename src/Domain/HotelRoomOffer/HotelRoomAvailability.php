<?php

namespace App\Domain\HotelRoomOffer;

use DateTimeImmutable;

class HotelRoomAvailability
{
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    private function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public static function of(DateTimeImmutable $start, DateTimeImmutable $end): self
    {
        $start = $start->setTime(0, 0);
        $end = $end->setTime(0, 0);

        if ($start > $end) {
            throw new HotelRoomAvailabilityException(sprintf(
                'Start date %s of availability is after end date %s.',
                $start->format('Y-m-d'),
                $end->format('Y-m-d'),
            ));
        }

        return new self($start, $end);
    }
}