<?php

namespace App\Infrastructure\Persistence\Sql\Booking;

use App\Domain\Apartment\Booking;
use App\Domain\Apartment\BookingRepository;

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
}