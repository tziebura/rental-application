<?php

namespace App\Application\HotelRoomOffer;

use App\Domain\Hotel\Hotel;
use App\Domain\Hotel\HotelRepository;
use App\Domain\Hotel\HotelRoomNotFoundException;
use App\Domain\HotelRoomOffer\CreateHotelRoomOffer;
use App\Domain\HotelRoomOffer\HotelRoomOffer;
use App\Domain\HotelRoomOffer\HotelRoomOfferBuilder;
use App\Domain\HotelRoomOffer\HotelRoomOfferDomainService;
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
        $hotelRoomOffer = (new HotelRoomOfferDomainService())->createHotelRoomOffer($hotel, $dto->asDto());

        $this->hotelRoomOfferRepository->save($hotelRoomOffer);
    }

}