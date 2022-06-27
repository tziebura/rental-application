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
}