<?php

namespace App\Domain\Apartment;

interface BookingRepository
{
    public function save(Booking $booking): void;
}