<?php

namespace App\Domain\Apartment;

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

    public function __construct(int $rentalPlaceId, string $tenantId, string $rentalType, array $dates)
    {
        $this->id = null;
        $this->rentalPlaceId = $rentalPlaceId;
        $this->tenantId = $tenantId;
        $this->rentalType = $rentalType;
        $this->dates = $dates;
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
}