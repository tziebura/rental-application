<?php

namespace App\Application\Hotel;

use DateTimeImmutable;

class HotelRoomBookingDTO
{
    private string $hotelId;
    private int $roomNumber;
    private string $tenantId;
    /** @var DateTimeImmutable[] */
    private array $days;

    public function __construct(string $hotelId, int $roomNumber, string $tenantId, array $days)
    {
        $this->hotelId = $hotelId;
        $this->roomNumber = $roomNumber;
        $this->tenantId = $tenantId;
        $this->days = $days;
    }

    public function getHotelId(): string
    {
        return $this->hotelId;
    }

    public function getRoomNumber(): int
    {
        return $this->roomNumber;
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    public function getDays(): array
    {
        return $this->days;
    }
}