<?php

namespace App\Domain\Booking;

use RuntimeException;

class NotAllowedBookingStatusTransitionException extends RuntimeException
{
    public static function with(string $from, string $to): self
    {
        return new self(sprintf('Not allowed to transition from %s to %s booking.', mb_strtoupper($from), mb_strtoupper($to)));
    }
}