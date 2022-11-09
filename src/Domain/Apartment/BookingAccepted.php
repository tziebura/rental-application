<?php

namespace App\Domain\Apartment;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class BookingAccepted
{
    private string $eventId;
    private DateTimeImmutable $eventCreationDateTime;
    private string $rentalType;
    private int $rentalPlaceId;
    private string $tenantId;
    private array $dates;

    private function __construct(string $eventId, DateTimeImmutable $eventCreationDateTime, string $rentalType, int $rentalPlaceId, string $tenantId, array $dates)
    {
        $this->eventId = $eventId;
        $this->eventCreationDateTime = $eventCreationDateTime;
        $this->rentalType = $rentalType;
        $this->rentalPlaceId = $rentalPlaceId;
        $this->tenantId = $tenantId;
        $this->dates = $dates;
    }

    public static function create(string $rentalType, int $rentalPlaceId, string $tenantId, array $dates): self
    {
        return new self(
            Uuid::uuid4()->toString(),
            new DateTimeImmutable(),
            $rentalType,
            $rentalPlaceId,
            $tenantId,
            $dates
        );
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