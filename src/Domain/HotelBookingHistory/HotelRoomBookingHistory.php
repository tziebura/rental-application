<?php

namespace App\Domain\HotelBookingHistory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @todo add ORM annotation
 */
class HotelRoomBookingHistory
{
    private int $hotelRoomId;
    private HotelBookingHistory $hotelBookingHistory;
    private Collection $bookings;

    public function __construct(int $hotelRoomId, HotelBookingHistory $hotelBookingHistory)
    {
        $this->hotelRoomId = $hotelRoomId;
        $this->hotelBookingHistory = $hotelBookingHistory;
        $this->bookings = new ArrayCollection();
    }

    public function add(HotelRoomBooking $booking): void
    {
        if ($this->bookings->contains($booking)) {
            return;
        }

        $this->bookings->add($booking);
        $booking->setHotelRoomBookingHistory($this);
    }

    public function getHotelRoomId(): int
    {
        return $this->hotelRoomId;
    }
}