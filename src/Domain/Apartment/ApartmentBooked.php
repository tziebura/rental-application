<?php

namespace App\Domain\Apartment;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class ApartmentBooked
{
    private string $eventId;
    private DateTimeImmutable $eventCreationDateTime;
    private int $id;
    private string $ownerId;
    private string $tenantId;
    private DateTimeImmutable $periodStart;
    private DateTimeImmutable $periodEnd;

    public function __construct(string $eventId, DateTimeImmutable $eventCreationDateTime, int $id, string $ownerId, string $tenantId, Period $period)
    {
        $this->eventId = $eventId;
        $this->eventCreationDateTime = $eventCreationDateTime;
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->tenantId = $tenantId;
        $this->periodStart = $period->getStart();
        $this->periodEnd = $period->getEnd();
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getEventCreationDateTime(): DateTimeImmutable
    {
        return $this->eventCreationDateTime;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    public function getPeriodStart(): DateTimeImmutable
    {
        return $this->periodStart;
    }

    public function getPeriodEnd(): DateTimeImmutable
    {
        return $this->periodEnd;
    }
}