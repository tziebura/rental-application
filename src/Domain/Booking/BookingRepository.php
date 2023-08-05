<?php

namespace App\Domain\Booking;

interface BookingRepository
{
    public function save(Booking $booking): void;
    public function findById(string $id): ?Booking;
}