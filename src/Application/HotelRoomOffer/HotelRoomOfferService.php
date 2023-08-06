<?php

namespace App\Application\HotelRoomOffer;

use App\Domain\HotelRoom\HotelRoomNotFoundException;
use App\Domain\HotelRoom\HotelRoomRepository;
use App\Domain\HotelRoomOffer\HotelRoomOfferBuilder;
use App\Domain\HotelRoomOffer\HotelRoomOfferRepository;

class HotelRoomOfferService
{
    private HotelRoomOfferRepository $hotelRoomOfferRepository;
    private HotelRoomRepository $hotelRoomRepository;

    public function __construct(HotelRoomOfferRepository $hotelRoomOfferRepository, HotelRoomRepository $hotelRoomRepository)
    {
        $this->hotelRoomOfferRepository = $hotelRoomOfferRepository;
        $this->hotelRoomRepository = $hotelRoomRepository;
    }

    public function add(HotelRoomOfferDTO $dto): void
    {
        if (!$this->hotelRoomRepository->existsById($dto->getHotelRoomId())) {
            throw new HotelRoomNotFoundException(sprintf('Hotel room with ID %s does not exist', $dto->getHotelRoomId()));
        }

        $hotelRoomOffer = HotelRoomOfferBuilder::create()
            ->withHotelRoomId($dto->getHotelRoomId())
            ->withPrice($dto->getPrice())
            ->withAvailability($dto->getStart(), $dto->getEnd())
            ->build();

        $this->hotelRoomOfferRepository->save($hotelRoomOffer);
    }

}