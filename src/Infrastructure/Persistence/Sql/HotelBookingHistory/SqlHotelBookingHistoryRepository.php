<?php

namespace App\Infrastructure\Persistence\Sql\HotelBookingHistory;

use App\Domain\HotelBookingHistory\HotelBookingHistory;
use App\Domain\HotelBookingHistory\HotelBookingHistoryRepository;

class SqlHotelBookingHistoryRepository implements HotelBookingHistoryRepository
{
    private DoctrineOrmHotelBookingHistoryRepository $repository;

    public function __construct(DoctrineOrmHotelBookingHistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findFor(string $hotelId): ?HotelBookingHistory
    {
        return $this->repository->find($hotelId);
    }

    public function save(HotelBookingHistory $bookingHistory): void
    {
        $this->repository->save($bookingHistory);
    }
}