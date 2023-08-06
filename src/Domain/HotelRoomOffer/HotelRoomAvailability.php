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
            throw HotelRoomAvailabilityException::startAfterEnd($start, $end);
        }

        $today = (new DateTimeImmutable())->setTime(0, 0);

        if ($start < $today) {
            throw new HotelRoomAvailabilityException(sprintf(
                'Start date must be at least today, %s given.',
                $start->format('Y-m-d'),
            ));
        }

        return new self($start, $end);
    }
}