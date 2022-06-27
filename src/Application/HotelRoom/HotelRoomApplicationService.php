<?php

namespace App\Application\HotelRoom;

use App\Domain\HotelRoom\HotelRoomFactory;
use App\Domain\HotelRoom\HotelRoomRepository;

class HotelRoomApplicationService
{
    private HotelRoomRepository $hotelRoomRepository;

    public function __construct(HotelRoomRepository $hotelRoomRepository)
    {
        $this->hotelRoomRepository = $hotelRoomRepository;
    }

    public function add(
        string $hotelId, int $number, string $description, array $rooms
    ): void {
        $factory = new HotelRoomFactory();
        $hotelRoom = $factory->create(
            $hotelId, $number, $description, $rooms);

        $this->hotelRoomRepository->save($hotelRoom);
    }
}