<?php

namespace App\Domain\ApartmentBookingHistory;

interface ApartmentBookingHistoryRepository
{
    public function findFor(string $apartmentId): ?ApartmentBookingHistory;
    public function save(ApartmentBookingHistory $bookingHistory): void;
}