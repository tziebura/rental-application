<?php

namespace App\Infrastructure\Web\Rest\Api\HotelRoom;

use DateTimeImmutable;

class HotelRoomBookingDto
{
    private array $days;
    private string $tenantId;

    public function __construct(array $days, string $tenantId)
    {
        $this->days = $days;
        $this->tenantId = $tenantId;
    }

    /**
     * @return DateTimeImmutable[]
     */
    public function getDays(): array
    {
        return $this->days;
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }
}