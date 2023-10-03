<?php

namespace App\Domain\Booking;

use App\Domain\Agreement\Agreement;
use App\Domain\Agreement\AgreementBuilder;
use App\Domain\Money\Money;
use App\Domain\Period\Period;
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

    /**
     * @ORM\Column()
     */
    private string $ownerId;

    /**
     * @ORM\Embedded(class="App\Domain\Money\Money")
     */
    private Money $price;

    public function __construct(int $rentalPlaceId, string $tenantId, string $rentalType, array $dates, string $ownerId, Money $price)
    {
        $this->id = null;
        $this->rentalPlaceId = $rentalPlaceId;
        $this->tenantId = $tenantId;
        $this->rentalType = $rentalType;
        $this->dates = $dates;
        $this->status = BookingStatus::OPEN;
        $this->ownerId = $ownerId;
        $this->price = $price;
    }

    public static function apartment(int $apartmentId, string $tenantId, Period $period, string $ownerId, Money $price): self
    {
        return new self(
            $apartmentId,
            $tenantId,
            RentalType::APARTMENT,
            $period->asDays(),
            $ownerId,
            $price
        );
    }

    public static function hotelRoom(int $hotelRoomId, string $tenantId, array $days, string $ownerId, Money $price): self
    {
        return new self(
            $hotelRoomId,
            $tenantId,
            RentalType::HOTEL_ROOM,
            $days,
            $ownerId,
            $price
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

    public function accept(BookingEventsPublisher $bookingEventsPublisher): Agreement
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

        return AgreementBuilder::create()
            ->withRentalType($this->rentalType)
            ->withRentalPlaceId($this->rentalPlaceId)
            ->withTenantId($this->tenantId)
            ->withOwnerId($this->ownerId)
            ->withDays($this->dates)
            ->withPrice($this->price)
            ->build();
    }

    public function hasCollisionWith(Booking $other): bool
    {
        foreach ($this->dates as $date) {
            if ($this->status === BookingStatus::ACCEPTED && in_array($date, $other->dates)) {
                return true;
            }
        }

        return false;
    }

    public function getRentalType(): string
    {
        return $this->rentalType;
    }

    public function getRentalPlaceId(): int
    {
        return $this->rentalPlaceId;
    }

    public function isFor(Period $period): bool
    {
        foreach ($this->dates as $date) {
            if (!$date instanceof \DateTimeInterface) {
                $date = \DateTimeImmutable::createFromFormat('Y-m-d', $date);
            }

            if ($period->contains($date)) {
                return true;
            }
        }

        return false;
    }
}