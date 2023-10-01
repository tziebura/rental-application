<?php

namespace App\Infrastructure\Persistence\Sql\Booking;

use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingRepository;
use App\Domain\Booking\BookingStatus;

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

    public function findAllBy(string $rentalType, int $rentalPlaceId): array
    {
        return $this->repository->findBy([
            'rentalType' => $rentalType,
            'rentalPlaceId' => $rentalPlaceId,
        ]);
    }

    public function findAllAcceptedBy(string $rentalType, int $rentalPlaceId): array
    {
        return $this->repository->findBy([
            'rentalType' => $rentalType,
            'rentalPlaceId' => $rentalPlaceId,
            'status' => BookingStatus::ACCEPTED,
        ]);
    }
}