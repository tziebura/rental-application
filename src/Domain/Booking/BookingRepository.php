<?php

namespace App\Domain\Booking;

interface BookingRepository
{
    public function save(Booking $booking): void;
    public function findById(string $id): ?Booking;

    /**
     * @param string $rentalType
     * @param int $rentalPlaceId
     * @return Booking[]
     */
    public function findAllBy(string $rentalType, int $rentalPlaceId): array;
}