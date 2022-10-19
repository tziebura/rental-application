<?php

namespace App\Domain\ApartmentBookingHistory;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="apartment_bookings")
 */
class ApartmentBooking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id;

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
    private string $ownerId;

    /**
     * @ORM\Column()
     */
    private string $tenantId;

    /**
     * @ORM\Embedded(class="BookingPeriod")
     */
    private BookingPeriod $bookingPeriod;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\ApartmentBookingHistory\ApartmentBookingHistory", inversedBy="bookings")
     * @ORM\JoinColumn(referencedColumnName="apartment_id", onDelete="CASCADE")
     */
    private ApartmentBookingHistory $apartmentBookingHistory;

    public function __construct(string $step, DateTimeImmutable $bookingDateTime, string $ownerId, string $tenantId, BookingPeriod $bookingPeriod)
    {
        $this->id = null;
        $this->step = $step;
        $this->bookingDateTime = $bookingDateTime;
        $this->ownerId = $ownerId;
        $this->tenantId = $tenantId;
        $this->bookingPeriod = $bookingPeriod;
    }

    public static function start(DateTimeImmutable $bookingDateTime, string $ownerId, string $tenantId, BookingPeriod $bookingPeriod): self
    {
        return new self(
            BookingStep::START,
            $bookingDateTime,
            $ownerId,
            $tenantId,
            $bookingPeriod
        );
    }

    public function setApartmentBookingHistory(ApartmentBookingHistory $apartmentBookingHistory): void
    {
        $this->apartmentBookingHistory = $apartmentBookingHistory;
    }
}