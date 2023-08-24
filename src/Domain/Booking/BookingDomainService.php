<?php

namespace App\Domain\Booking;

class BookingDomainService
{
    private BookingEventsPublisher $bookingEventsPublisher;

    public function __construct(BookingEventsPublisher $bookingEventsPublisher)
    {
        $this->bookingEventsPublisher = $bookingEventsPublisher;
    }

    public function accept(Booking $booking, array $bookings)
    {
        if (empty($bookings)) {
            $booking->accept($this->bookingEventsPublisher);
        } else {
            $booking->reject($this->bookingEventsPublisher);
        }
    }
}