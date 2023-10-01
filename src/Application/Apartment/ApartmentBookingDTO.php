<?php

namespace App\Application\Apartment;

use App\Domain\Apartment\NewApartmentBookingDto;
use DateTimeImmutable;

class ApartmentBookingDTO
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

    public function asNewApartmentBookingDto(): NewApartmentBookingDto
    {
        return new NewApartmentBookingDto(
            $this->apartmentId,
            $this->tenantId,
            $this->start,
            $this->end
        );
    }
}