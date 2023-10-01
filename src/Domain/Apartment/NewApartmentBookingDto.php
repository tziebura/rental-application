<?php

namespace App\Domain\Apartment;

use DateTimeImmutable;

class NewApartmentBookingDto
{
    private string $apartmentId;
    private string $tenantId;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    public function __construct(string $apartmentId, string $tenantId, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->apartmentId = $apartmentId;
        $this->tenantId = $tenantId;
        $this->start = $start;
        $this->end = $end;
    }

    public function getApartmentId(): string
    {
        return $this->apartmentId;
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