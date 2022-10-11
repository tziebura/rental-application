<?php

namespace App\Infrastructure\Web\Rest\Api\Apartment;

use DateTimeImmutable;

class ApartmentBookingDto
{
    private string $tenantId;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    public function __construct(string $tenantId, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->tenantId = $tenantId;
        $this->start = $start;
        $this->end = $end;
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): DateTimeImmutable
    {
        return $this->end;
    }
}