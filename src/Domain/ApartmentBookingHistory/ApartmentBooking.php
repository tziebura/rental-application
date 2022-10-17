<?php

namespace App\Domain\ApartmentBookingHistory;

use DateTimeImmutable;

/**
 * @todo add ORM annotation
 */
class ApartmentBooking
{

    private ?int $id;
    private string $step;
    private DateTimeImmutable $bookingDateTime;
    private string $ownerId;
    private string $tenantId;
    private BookingPeriod $bookingPeriod;
    private ApartmentBookingHistory $apartmentBookingHistory;

    public function __construct(string $step, DateTimeImmutable $bookingDateTime, string $ownerId, string $tenantId, BookingPeriod $bookingPeriod)
    {
        $this->id = null;
        $this->step = $step;
        $this->bookingDateTime = $bookingDateTime;
        $this->ownerId = $ownerId;
        $this->tenantId = $tenantId;
        $this->bookingPeriod = $bookingPeriod;
    }

    public static function start(DateTimeImmutable $bookingDateTime, string $ownerId, string $tenantId, BookingPeriod $bookingPeriod): self
    {
        return new self(
            BookingStep::START,
            $bookingDateTime,
            $ownerId,
            $tenantId,
            $bookingPeriod
        );
    }

    public function setApartmentBookingHistory(ApartmentBookingHistory $apartmentBookingHistory): void
    {
        $this->apartmentBookingHistory = $apartmentBookingHistory;
    }
}