<?php

namespace App\Domain\HotelBookingHistory;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="hotel_booking_histories")
 */
class HotelBookingHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer")
     */
    private string $hotelId;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\HotelBookingHistory\HotelRoomBookingHistory", mappedBy="hotelBookingHistory", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $hotelRoomBookingHistories;

    public function __construct(string $hotelId)
    {
        $this->hotelId = $hotelId;
        $this->hotelRoomBookingHistories = new ArrayCollection();
    }

    public function add(int $hotelRoomId, DateTimeImmutable $bookingDateTime, string $tenantId, array $days)
    {
        $booking = $this->findFor($hotelRoomId);

        $booking->add(HotelRoomBooking::start(
            $bookingDateTime,
            $tenantId,
            $days
        ));
    }

    private function findFor(int $hotelRoomId): HotelRoomBookingHistory
    {
        $booking = $this->hotelRoomBookingHistories
            ->filter(fn (HotelRoomBookingHistory $history) => $history->getHotelRoomId() === $hotelRoomId)
            ->first();

        if (!$booking) {
            $booking = new HotelRoomBookingHistory(
                $hotelRoomId,
                $this
            );

            $this->hotelRoomBookingHistories->add($booking);
        }

        return $booking;
    }
}