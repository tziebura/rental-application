<?php

namespace App\Domain\Period;

use DateTimeImmutable;
use RuntimeException;

class PeriodException extends RuntimeException
{
    public static function startDateFromPast(DateTimeImmutable $startDate): self
    {
        return new self(sprintf('Start date: %s is from the past.', $startDate->format('Y-m-d')));
    }

    public static function startDateAfterEndDate(DateTimeImmutable $start, DateTimeImmutable $end): self
    {
        return new self(sprintf(
            'Start date: %s of period is after end date: %s.',
            $start->format('Y-m-d'),
            $end->format('Y-m-d')
        ));
    }
}