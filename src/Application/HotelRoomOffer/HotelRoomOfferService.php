<?php

namespace App\Application\HotelRoomOffer;

use App\Domain\Hotel\HotelRepository;
use App\Domain\HotelRoomOffer\HotelRoomOfferDomainService;
use App\Domain\HotelRoomOffer\HotelRoomOfferRepository;

class HotelRoomOfferService
{
    private HotelRoomOfferRepository $hotelRoomOfferRepository;
    private HotelRepository $hotelRepository;
    private HotelRoomOfferDomainService $hotelRoomOfferDomainService;

    public function __construct(
        HotelRoomOfferRepository $hotelRoomOfferRepository,
        HotelRepository $hotelRoomRepository,
        HotelRoomOfferDomainService $hotelRoomOfferDomainService
    ) {
        $this->hotelRoomOfferRepository = $hotelRoomOfferRepository;
        $this->hotelRepository = $hotelRoomRepository;
        $this->hotelRoomOfferDomainService = $hotelRoomOfferDomainService;
    }

    public function add(HotelRoomOfferDTO $dto): void
    {
        $hotel = $this->hotelRepository->findById($dto->getHotelId());
        $hotelRoomOffer = $this->hotelRoomOfferDomainService->createHotelRoomOffer($hotel, $dto->asDto());

        $this->hotelRoomOfferRepository->save($hotelRoomOffer);
    }

}