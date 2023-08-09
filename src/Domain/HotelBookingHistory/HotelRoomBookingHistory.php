<?php

namespace App\Domain\HotelBookingHistory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="hotel_room_booking_histories")
 */
class HotelRoomBookingHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer")
     */
    private int $hotelRoomId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\HotelBookingHistory\HotelBookingHistory", inversedBy="hotelRoomBookingHistories")
     * @ORM\JoinColumn(referencedColumnName="hotel_id", onDelete="CASCADE")
     */
    private HotelBookingHistory $hotelBookingHistory;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\HotelBookingHistory\HotelRoomBooking", mappedBy="bookingHistory", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $bookings;

    public function __construct(int $hotelRoomId, HotelBookingHistory $hotelBookingHistory)
    {
        $this->hotelRoomId = $hotelRoomId;
        $this->hotelBookingHistory = $hotelBookingHistory;
        $this->bookings = new ArrayCollection();
    }

    public function add(HotelRoomBooking $booking): void
    {
        $this->bookings->add($booking);
        $booking->setHotelRoomBookingHistory($this);
    }

    public function getHotelRoomId(): int
    {
        return $this->hotelRoomId;
    }
}