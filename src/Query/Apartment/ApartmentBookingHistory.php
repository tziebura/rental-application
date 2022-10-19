<?php

namespace App\Query\Apartment;

class ApartmentBookingHistory
{
    private int $apartmentId;
    private array $bookings;

    public function __construct(int $apartmentId, array $bookings)
    {
        $this->apartmentId = $apartmentId;
        $this->bookings = $bookings;
    }

    public static function fromArray(array $data)
    {
        return new self(
            (int) $data['apartment_id'],
            array_map(fn(array $booking) => ApartmentBooking::fromArray($booking), $data['bookings'])
        );
    }

    public function getApartmentId(): string
    {
        return $this->apartmentId;
    }

    public function getBookings(): array
    {
        return $this->bookings;
    }
}