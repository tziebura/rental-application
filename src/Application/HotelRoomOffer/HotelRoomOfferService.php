<?php

namespace App\Application\HotelRoomOffer;

use App\Domain\HotelRoomOffer\HotelRoomOfferBuilder;
use App\Domain\HotelRoomOffer\HotelRoomOfferRepository;

class HotelRoomOfferService
{
    private HotelRoomOfferRepository $hotelRoomOfferRepository;

    public function __construct(HotelRoomOfferRepository $hotelRoomOfferRepository)
    {
        $this->hotelRoomOfferRepository = $hotelRoomOfferRepository;
    }

    public function add(HotelRoomOfferDTO $dto): void
    {
        $hotelRoomOffer = HotelRoomOfferBuilder::create()
            ->withHotelRoomId($dto->getHotelRoomId())
            ->withPrice($dto->getPrice())
            ->withAvailability($dto->getStart(), $dto->getEnd())
            ->build();

        $this->hotelRoomOfferRepository->save($hotelRoomOffer);
    }

}