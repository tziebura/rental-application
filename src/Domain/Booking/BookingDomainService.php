<?php

namespace App\Domain\Booking;

class BookingDomainService
{
    private BookingEventsPublisher $bookingEventsPublisher;

    public function __construct(BookingEventsPublisher $bookingEventsPublisher)
    {
        $this->bookingEventsPublisher = $bookingEventsPublisher;
    }

    /**
     * @param Booking $booking
     * @param Booking[] $bookings
     * @return void
     */
    public function accept(Booking $booking, array $bookings)
    {
        if ($this->canAcceptBooking($booking, $bookings)) {
            $booking->accept($this->bookingEventsPublisher);
        } else {
            $booking->reject($this->bookingEventsPublisher);
        }
    }

    /**
     * @param Booking $bookingToAccept
     * @param Booking[] $bookings
     * @return bool
     */
    private function canAcceptBooking(Booking $bookingToAccept, array $bookings): bool
    {
        if (empty($bookings)) {
            return true;
        }

        foreach ($bookings as $booking) {
            if ($booking->hasCollisionWith($bookingToAccept)) {
                return false;
            }
        }

        return true;
    }
}