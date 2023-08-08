<?php

namespace App\Application\HotelRoomOffer;

use App\Domain\Hotel\HotelRepository;
use App\Domain\Hotel\HotelRoomNotFoundException;
use App\Domain\HotelRoomOffer\HotelRoomOfferBuilder;
use App\Domain\HotelRoomOffer\HotelRoomOfferRepository;

class HotelRoomOfferService
{
    private HotelRoomOfferRepository $hotelRoomOfferRepository;
    private HotelRepository $hotelRepository;

    public function __construct(HotelRoomOfferRepository $hotelRoomOfferRepository, HotelRepository $hotelRoomRepository)
    {
        $this->hotelRoomOfferRepository = $hotelRoomOfferRepository;
        $this->hotelRepository = $hotelRoomRepository;
    }

    public function add(HotelRoomOfferDTO $dto): void
    {
        $hotel = $this->hotelRepository->findById($dto->getHotelId());

        if (!$hotel->hasRoomWithNumber($dto->getHotelRoomNumber())) {
            throw HotelRoomNotFoundException::withNumber($dto->getHotelRoomNumber());
        }

        $hotelRoomOffer = HotelRoomOfferBuilder::create()
            ->withHotelId($dto->getHotelId())
            ->withHotelRoomNumber($dto->getHotelRoomNumber())
            ->withPrice($dto->getPrice())
            ->withAvailability($dto->getStart(), $dto->getEnd())
            ->build();

        $this->hotelRoomOfferRepository->save($hotelRoomOffer);
    }

}