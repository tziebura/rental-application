<?php

namespace App\Domain\Apartment;

use App\Domain\Money\Money;
use App\Domain\Period\Period;

class ApartmentBooking
{
    private string $tenantId;
    private Period $period;
    private Money $price;
    private ApartmentEventsPublisher $apartmentEventsPublisher;

    public function __construct(string $tenantId, Period $period, Money $price, ApartmentEventsPublisher $apartmentEventsPublisher)
    {
        $this->tenantId = $tenantId;
        $this->period = $period;
        $this->price = $price;
        $this->apartmentEventsPublisher = $apartmentEventsPublisher;
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    public function getPeriod(): Period
    {
        return $this->period;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getApartmentEventsPublisher(): ApartmentEventsPublisher
    {
        return $this->apartmentEventsPublisher;
    }
}