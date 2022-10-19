<?php

namespace App\Query\Apartment;

/**
 * @todo add ORM annotations.
 */
class ApartmentDetails
{
    private Apartment $apartment;
    private ApartmentBookingHistory $bookingHistory;

    public function __construct(Apartment $apartment, ApartmentBookingHistory $bookingHistory)
    {
        $this->apartment = $apartment;
        $this->bookingHistory = $bookingHistory;
    }

    public function getApartment(): Apartment
    {
        return $this->apartment;
    }

    public function getBookingHistory(): ApartmentBookingHistory
    {
        return $this->bookingHistory;
    }
}