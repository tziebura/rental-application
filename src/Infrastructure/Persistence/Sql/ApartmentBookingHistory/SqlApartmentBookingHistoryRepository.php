<?php

namespace App\Infrastructure\Persistence\Sql\ApartmentBookingHistory;

use App\Domain\ApartmentBookingHistory\ApartmentBookingHistory;
use App\Domain\ApartmentBookingHistory\ApartmentBookingHistoryRepository;

class SqlApartmentBookingHistoryRepository implements ApartmentBookingHistoryRepository
{
    private DoctrineOrmApartmentBookingHistoryRepository $repository;

    public function __construct(DoctrineOrmApartmentBookingHistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findFor(string $apartmentId): ?ApartmentBookingHistory
    {
        return $this->repository->find($apartmentId);
    }

    public function save(ApartmentBookingHistory $bookingHistory): void
    {
        $this->repository->save($bookingHistory);
    }
}