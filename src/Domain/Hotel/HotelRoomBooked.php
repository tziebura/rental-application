<?php

namespace App\Domain\Hotel;

use DateTimeImmutable;

class HotelRoomBooked
{
    private string $eventId;
    private DateTimeImmutable $eventCreationDateTime;
    private int $id;
    private string $hotelId;
    private array $days;
    private string $tenantId;

    public function __construct(string $eventId, DateTimeImmutable $eventCreationDateTime, int $id, string $hotelId, array $days, string $tenantId)
    {
        $this->eventId = $eventId;
        $this->eventCreationDateTime = $eventCreationDateTime;
        $this->id = $id;
        $this->hotelId = $hotelId;
        $this->days = $days;
        $this->tenantId = $tenantId;
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

    public function getHotelId(): string
    {
        return $this->hotelId;
    }

    public function getDays(): array
    {
        return $this->days;
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }
}