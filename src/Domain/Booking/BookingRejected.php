<?php

namespace App\Domain\Booking;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class BookingRejected
{
    private string $eventId;
    private DateTimeImmutable $eventCreationDateTime;
    private string $rentalType;
    private int $rentalPlaceId;
    private string $tenantId;
    private array $dates;

    public function __construct(string $eventId, DateTimeImmutable $eventCreationDateTime, string $rentalType, int $rentalPlaceId, string $tenantId, array $dates)
    {
        $this->eventId = $eventId;
        $this->eventCreationDateTime = $eventCreationDateTime;
        $this->rentalType = $rentalType;
        $this->rentalPlaceId = $rentalPlaceId;
        $this->tenantId = $tenantId;
        $this->dates = $dates;
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getEventCreationDateTime(): DateTimeImmutable
    {
        return $this->eventCreationDateTime;
    }
    public function getRentalType(): string
    {
        return $this->rentalType;
    }

    public function getRentalPlaceId(): int
    {
        return $this->rentalPlaceId;
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    public function getDates(): array
    {
        return $this->dates;
    }
}