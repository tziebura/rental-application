<?php

namespace App\Domain\ApartmentOffer;

use DateTimeImmutable;

class ApartmentAvailability
{
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->start = $start;
        $this->end = $end;
    }
}