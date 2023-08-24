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
        if ($this->canAcceptBooking($booking, $bookings)) {
            $booking->accept($this->bookingEventsPublisher);
        } else {
            $booking->reject($this->bookingEventsPublisher);
        }
    }

    private function canAcceptBooking(Booking $bookingToAccept, array $bookings): bool
    {
        if (empty($bookings)) {
            return true;
        }

        foreach ($bookings as $booking) {
            if ($booking->hasCollisionWith($bookingToAccept)) {
                return true;
            }
        }

        return false;
    }
}