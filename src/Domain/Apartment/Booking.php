<?php

namespace App\Domain\Apartment;

use App\Domain\EventChannel\EventChannel;

/**
 * @todo add ORM annotation
 */
class Booking
{
    private ?int $id;
    private int $rentalPlaceId;
    private string $tenantId;
    private string $rentalType;
    private array $dates;
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

    public function reject()
    {
        $this->status = BookingStatus::REJECTED;
    }

    public function accept(EventChannel $eventChannel)
    {
        $this->status = BookingStatus::ACCEPTED;

        $event = BookingAccepted::create(
            $this->rentalType,
            $this->rentalPlaceId,
            $this->tenantId,
            $this->dates
        );

        $eventChannel->publish($event);
    }
}