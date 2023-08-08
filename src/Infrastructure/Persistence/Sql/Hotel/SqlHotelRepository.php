<?php

namespace App\Infrastructure\Persistence\Sql\Hotel;

use App\Domain\Hotel\Hotel;
use App\Domain\Hotel\HotelRepository;

class SqlHotelRepository implements HotelRepository
{
    private DoctrineOrmHotelRepository $doctrineOrmHotelRepository;

    public function __construct(DoctrineOrmHotelRepository $doctrineOrmHotelRepository)
    {
        $this->doctrineOrmHotelRepository = $doctrineOrmHotelRepository;
    }

    public function save(Hotel $hotel): void
    {
        $this->doctrineOrmHotelRepository->save($hotel);
    }

    public function findById(string $hotelId): ?Hotel
    {
        return $this->doctrineOrmHotelRepository->find($hotelId);
    }
}