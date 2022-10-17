<?php

namespace App\Domain\ApartmentBookingHistory;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @todo add ORM annotation
 */
class ApartmentBookingHistory
{
    private string $apartmentId;
    private string $bookings;

    public function __construct(string $apartmentId)
    {
        $this->apartmentId = $apartmentId;
        $this->bookings = new ArrayCollection();
    }

    public function add(ApartmentBooking $booking): void
    {
        if ($this->bookings->contains($booking)) {
            return;
        }

        $this->bookings->add($booking);
        $booking->setApartmentBookingHistory($this);
    }
}