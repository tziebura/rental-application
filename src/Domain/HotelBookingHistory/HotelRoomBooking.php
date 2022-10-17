<?php

namespace App\Domain\HotelBookingHistory;

use DateTimeImmutable;

/**
 * @todo add ORM annotation
 */
class HotelRoomBooking
{
    private string $step;
    private DateTimeImmutable $bookingDateTime;
    private string $tenantId;
    private array $days;
    private HotelRoomBookingHistory $bookingHistory;

    private function __construct(string $step, DateTimeImmutable $bookingDateTime, string $tenantId, array $days)
    {
        $this->step = $step;
        $this->bookingDateTime = $bookingDateTime;
        $this->tenantId = $tenantId;
        $this->days = $days;
    }

    public static function start(DateTimeImmutable $bookingDateTime, string $tenantId, array $days): self
    {
        return new self(
            BookingStep::START,
            $bookingDateTime,
            $tenantId,
            $days
        );
    }

    public function setHotelRoomBookingHistory(HotelRoomBookingHistory $booking): void
    {
        $this->bookingHistory = $booking;
    }
}