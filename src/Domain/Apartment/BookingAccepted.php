<?php

namespace App\Domain\Apartment;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class BookingAccepted
{
    private string $rentalType;
    private int $rentalPlaceId;
    private string $tenantId;
    private array $dates;
    private string $eventId;
    private DateTimeImmutable $eventDateTime;

    private function __construct(string $eventId, DateTimeImmutable $eventDateTime, string $rentalType, int $rentalPlaceId, string $tenantId, array $dates)
    {
        $this->rentalType = $rentalType;
        $this->rentalPlaceId = $rentalPlaceId;
        $this->tenantId = $tenantId;
        $this->dates = $dates;
        $this->eventId = $eventId;
        $this->eventDateTime = $eventDateTime;
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