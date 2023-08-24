<?php

namespace App\Domain\Booking;

use App\Domain\Period\Period;
use App\Domain\EventChannel\EventChannel;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $rentalPlaceId;

    /**
     * @ORM\Column()
     */
    private string $tenantId;

    /**
     * @ORM\Column()
     */
    private string $rentalType;

    /**
     * @ORM\Column(type="array")
     */
    private array $dates;

    /**
     * @ORM\Column()
     */
    private string $status;

    public function __construct(int $rentalPlaceId, string $tenantId, string $rentalType, array $dates)
    {
        $this->id = null;
        $this->rentalPlaceId = $rentalPlaceId;
        $this->tenantId = $tenantId;
        $this->rentalType = $rentalType;
        $this->dates = $dates;
        $this->status = BookingStatus::OPEN;
    }

    public static function apartment(int $apartmentId, string $tenantId, Period $period): self
    {
        return new self(
            $apartmentId,
            $tenantId,
            RentalType::APARTMENT,
            $period->asDays()
        );
    }

    public static function hotelRoom(int $hotelRoomId, string $tenantId, array $days): self
    {
        return new self(
            $hotelRoomId,
            $tenantId,
            RentalType::HOTEL_ROOM,
            $days
        );
    }

    public function reject(BookingEventsPublisher $bookingEventsPublisher)
    {
        if ($this->status === BookingStatus::ACCEPTED) {
            throw NotAllowedBookingStatusTransitionException::with($this->status, BookingStatus::REJECTED);
        }

        $this->status = BookingStatus::REJECTED;

        $bookingEventsPublisher->publishBookingRejected(
            $this->rentalType,
            $this->rentalPlaceId,
            $this->tenantId,
            $this->dates
        );
    }

    public function accept(BookingEventsPublisher $bookingEventsPublisher)
    {
        if ($this->status === BookingStatus::REJECTED) {
            throw NotAllowedBookingStatusTransitionException::with($this->status, BookingStatus::ACCEPTED);
        }

        $this->status = BookingStatus::ACCEPTED;

        $bookingEventsPublisher->publishBookingAccepted(
            $this->rentalType,
            $this->rentalPlaceId,
            $this->tenantId,
            $this->dates
        );
    }
}