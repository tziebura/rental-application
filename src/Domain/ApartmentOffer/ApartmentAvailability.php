<?php

namespace App\Domain\ApartmentOffer;

use DateTimeImmutable;

class ApartmentAvailability
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
        $start = $start->setTime(0,0);
        $end = $end->setTime(0, 0);

        if ($start > $end) {
            throw ApartmentAvailabilityException::startAfterEnd($start, $end);
        }

        return new self($start, $end);
    }
}