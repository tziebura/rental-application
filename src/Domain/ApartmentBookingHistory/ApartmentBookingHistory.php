<?php

namespace App\Domain\ApartmentBookingHistory;

use App\Domain\Period\Period;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="apartment_booking_histories")
 */
class ApartmentBookingHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer")
     */
    private int $apartmentId;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\ApartmentBookingHistory\ApartmentBooking", mappedBy="apartmentBooking", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $bookings;

    public function __construct(int $apartmentId)
    {
        $this->apartmentId = $apartmentId;
        $this->bookings = new ArrayCollection();
    }

    public function addBookingStart(DateTimeImmutable $bookingDateTime, string $ownerId, string $tenantId, Period $bookingPeriod): void
    {
        $this->add(ApartmentBooking::start(
            $bookingDateTime,
            $ownerId,
            $tenantId,
            $bookingPeriod
        ));
    }

    private function add(ApartmentBooking $booking): void
    {
        $this->bookings->add($booking);
        $booking->setApartmentBookingHistory($this);
    }
}