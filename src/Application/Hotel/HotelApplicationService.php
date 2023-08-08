<?php

namespace App\Application\Hotel;

use App\Domain\Booking\BookingRepository;
use App\Domain\Hotel\HotelEventsPublisher;
use App\Domain\Hotel\HotelFactory;
use App\Domain\Hotel\HotelRepository;

class HotelApplicationService
{
    private HotelRepository $hotelRepository;
    private HotelEventsPublisher $hotelEventsPublisher;
    private BookingRepository $bookingRepository;

    public function __construct(HotelRepository $hotelRepository, HotelEventsPublisher $hotelEventsPublisher, BookingRepository $bookingRepository)
    {
        $this->hotelRepository = $hotelRepository;
        $this->hotelEventsPublisher = $hotelEventsPublisher;
        $this->bookingRepository = $bookingRepository;
    }

    public function add(
        string $name, string $street, string $buildingNumber, string $postalCode, string $city, string $country
    ): void {
        $factory = new HotelFactory();
        $hotel = $factory->create(
            $name, $street, $buildingNumber, $postalCode, $city, $country);

        $this->hotelRepository->save($hotel);
    }

    public function addHotelRoom(HotelRoomDTO $dto): void {
        $hotel = $this->hotelRepository->findById($dto->getHotelId());
        $hotel->addHotelRoom(
            $dto->getNumber(),
            $dto->getDescription(),
            $dto->getRooms()
        );

        $this->hotelRepository->save($hotel);
    }

    public function book(HotelRoomBookingDTO $dto): void
    {
        $hotel = $this->hotelRepository->findById($dto->getHotelId());
        $booking = $hotel->bookRoom(
            $dto->getRoomNumber(), $dto->getTenantId(), $dto->getDays(), $this->hotelEventsPublisher
        );

        $this->bookingRepository->save($booking);
    }
}