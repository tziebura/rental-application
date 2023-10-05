<?php

namespace App\Domain\Agreement;

use DateTimeImmutable;

class AgreementAccepted
{
    private string $eventId;
    private DateTimeImmutable $eventCreationDateTime;
    private string $rentalType;
    private string $rentalPlaceId;
    private string $ownerId;
    private string $tenantId;
    private float $price;
    private array $days;

    public function __construct(string $eventId, DateTimeImmutable $eventCreationDateTime, string $rentalType, string $rentalPlaceId, string $ownerId, string $tenantId, float $price, array $days)
    {
        $this->eventId = $eventId;
        $this->eventCreationDateTime = $eventCreationDateTime;
        $this->rentalType = $rentalType;
        $this->rentalPlaceId = $rentalPlaceId;
        $this->ownerId = $ownerId;
        $this->tenantId = $tenantId;
        $this->price = $price;
        $this->days = $days;
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

    public function getRentalPlaceId(): string
    {
        return $this->rentalPlaceId;
    }

    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDays(): array
    {
        return $this->days;
    }
}