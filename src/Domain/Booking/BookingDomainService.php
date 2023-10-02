<?php

namespace App\Domain\Booking;

use App\Domain\Agreement\Agreement;
use App\Domain\Agreement\AgreementRepository;

class BookingDomainService
{
    private BookingEventsPublisher $bookingEventsPublisher;
    private AgreementRepository $agreementRepository;

    public function __construct(BookingEventsPublisher $bookingEventsPublisher, AgreementRepository $agreementRepository)
    {
        $this->bookingEventsPublisher = $bookingEventsPublisher;
        $this->agreementRepository = $agreementRepository;
    }

    /**
     * @param Booking $booking
     * @param Booking[] $bookings
     * @return Agreement|null
     */
    public function accept(Booking $booking, array $bookings): ?Agreement
    {
        if ($this->canAcceptBooking($booking, $bookings)) {
            $agreement = $booking->accept($this->bookingEventsPublisher);
            $this->agreementRepository->save($agreement);
            return $agreement;
        } else {
            $booking->reject($this->bookingEventsPublisher);
            return null;
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