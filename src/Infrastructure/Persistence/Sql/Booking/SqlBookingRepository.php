<?php

namespace App\Infrastructure\Persistence\Sql\Booking;

use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingRepository;

class SqlBookingRepository implements BookingRepository
{
    private DoctrineOrmBookingRepository $repository;

    public function __construct(DoctrineOrmBookingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function save(Booking $booking): void
    {
        $this->repository->save($booking);
    }

    public function findById(string $id): ?Booking
    {
        return $this->repository->find($id);
    }
}