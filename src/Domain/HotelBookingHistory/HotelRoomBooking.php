<?php

namespace App\Domain\HotelBookingHistory;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="hotel_room_bookings")
 */
class HotelRoomBooking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column()
     */
    private string $step;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $bookingDateTime;

    /**
     * @ORM\Column()
     */
    private string $tenantId;

    /**
     * @ORM\Column(type="json")
     */
    private array $days;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\HotelBookingHistory\HotelRoomBookingHistory", inversedBy="bookings")
     * @ORM\JoinColumn(referencedColumnName="hotel_room_id", onDelete="CASCADE")
     */
    private HotelRoomBookingHistory $bookingHistory;

    private function __construct(string $step, DateTimeImmutable $bookingDateTime, string $tenantId, array $days)
    {
        $this->step = $step;
        $this->bookingDateTime = $bookingDateTime;
        $this->tenantId = $tenantId;
        $this->days = $days;
    }

    public static function start(DateTimeImmutable $bookingDateTime, string $tenantId, array $days): self
    {
        return new self(
            BookingStep::START,
            $bookingDateTime,
            $tenantId,
            $days
        );
    }

    public function setHotelRoomBookingHistory(HotelRoomBookingHistory $booking): void
    {
        $this->bookingHistory = $booking;
    }
}