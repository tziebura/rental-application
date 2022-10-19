<?php

namespace App\Domain\ApartmentBookingHistory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @todo add ORM annotation
 */
class ApartmentBookingHistory
{
    private string $apartmentId;
    private Collection $bookings;

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