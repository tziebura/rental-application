<?php

namespace App\Infrastructure\Persistence\Sql\HotelRoom;

use App\Domain\HotelRoom\HotelRoom;
use App\Domain\HotelRoom\HotelRoomRepository;

class SqlHotelRoomRepository implements HotelRoomRepository
{
    private DoctrineOrmHotelRoomRepository $doctrineOrmHotelRoomRepository;

    public function __construct(DoctrineOrmHotelRoomRepository $doctrineOrmHotelRoomRepository)
    {
        $this->doctrineOrmHotelRoomRepository = $doctrineOrmHotelRoomRepository;
    }

    public function save(HotelRoom $hotelRoom): void
    {
        $this->doctrineOrmHotelRoomRepository->save($hotelRoom);
    }

    public function findById(string $id): ?HotelRoom
    {
        return $this->doctrineOrmHotelRoomRepository->find($id);
    }

    public function existsById(string $id): bool
    {
        return $this->doctrineOrmHotelRoomRepository->count(['id' => $id]) > 0;
    }
}