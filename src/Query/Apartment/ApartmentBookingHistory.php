<?php

namespace App\Query\Apartment;

use Doctrine\Common\Collections\Collection;

/**
 * @todo add ORM annotations.
 */
class ApartmentBookingHistory
{
    private string $apartmentId;
    private Collection $bookings;

    public function __construct(string $apartmentId, Collection $bookings)
    {
        $this->apartmentId = $apartmentId;
        $this->bookings = $bookings;
    }

    public function getApartmentId(): string
    {
        return $this->apartmentId;
    }

    public function getBookings(): Collection
    {
        return $this->bookings;
    }
}