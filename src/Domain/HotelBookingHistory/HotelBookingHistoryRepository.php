<?php

namespace App\Domain\HotelBookingHistory;

interface HotelBookingHistoryRepository
{

    public function findFor(string $hotelId): ?HotelBookingHistory;
    public function save(HotelBookingHistory $bookingHistory): void;
}