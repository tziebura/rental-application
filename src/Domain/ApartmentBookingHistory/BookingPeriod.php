<?php

namespace App\Domain\ApartmentBookingHistory;

use DateTimeImmutable;
use InvalidArgumentException;

class BookingPeriod
{
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($start > $end) {
            throw new InvalidArgumentException('Start cannot be greater than end');
        }

        $this->start = $start;
        $this->end = $end;
    }

}