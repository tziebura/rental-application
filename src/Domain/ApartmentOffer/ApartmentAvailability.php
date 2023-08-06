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
        if ($start > $end) {
            throw ApartmentAvailabilityException::startAfterEnd();
        }

        return new self($start, $end);
    }
}