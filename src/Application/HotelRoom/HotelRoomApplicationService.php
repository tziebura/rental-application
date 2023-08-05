<?php

namespace App\Application\HotelRoom;

use App\Domain\Apartment\BookingRepository;
use App\Domain\EventChannel\EventChannel;
use App\Domain\HotelRoom\HotelRoomEventsPublisher;
use App\Domain\HotelRoom\HotelRoomFactory;
use App\Domain\HotelRoom\HotelRoomRepository;

class HotelRoomApplicationService
{
    private HotelRoomRepository $hotelRoomRepository;
    private HotelRoomEventsPublisher $hotelRoomEventsPublisher;
    private BookingRepository $bookingRepository;

    public function __construct(
        HotelRoomRepository $hotelRoomRepository,
        HotelRoomEventsPublisher $hotelRoomEventsPublisher,
        BookingRepository $bookingRepository
    ) {
        $this->hotelRoomRepository = $hotelRoomRepository;
        $this->hotelRoomEventsPublisher = $hotelRoomEventsPublisher;
        $this->bookingRepository = $bookingRepository;
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
        $booking = $hotelRoom->book($days, $tenantId, $this->hotelRoomEventsPublisher);

        $this->bookingRepository->save($booking);
    }
}