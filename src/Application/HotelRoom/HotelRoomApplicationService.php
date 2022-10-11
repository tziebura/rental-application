<?php

namespace App\Application\HotelRoom;

use App\Domain\EventChannel\EventChannel;
use App\Domain\HotelRoom\HotelRoomFactory;
use App\Domain\HotelRoom\HotelRoomRepository;

class HotelRoomApplicationService
{
    private HotelRoomRepository $hotelRoomRepository;
    private EventChannel $eventChannel;

    public function __construct(HotelRoomRepository $hotelRoomRepository, EventChannel $eventChannel)
    {
        $this->hotelRoomRepository = $hotelRoomRepository;
        $this->eventChannel = $eventChannel;
    }

    public function add(
        string $hotelId, int $number, string $description, array $rooms
    ): void {
        $factory = new HotelRoomFactory();
        $hotelRoom = $factory->create(
            $hotelId, $number, $description, $rooms);

        $this->hotelRoomRepository->save($hotelRoom);
    }

    public function book(string $id, array $days, string $tenantId)
    {
        $hotelRoom = $this->hotelRoomRepository->findById($id);
        $hotelRoom->book($days, $tenantId, $this->eventChannel);
    }
}