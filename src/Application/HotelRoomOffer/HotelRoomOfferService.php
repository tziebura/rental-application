<?php

namespace App\Application\HotelRoomOffer;

use App\Domain\HotelRoomOffer\HotelRoomOfferRepository;
use DateTimeImmutable;

class HotelRoomOfferService
{
    private HotelRoomOfferRepository $hotelRoomOfferRepository;

    public function __construct(HotelRoomOfferRepository $hotelRoomOfferRepository)
    {
        $this->hotelRoomOfferRepository = $hotelRoomOfferRepository;
    }

    public function add(string $hotelRoomId, float $price, DateTimeImmutable $start, DateTimeImmutable $end)
    {

    }

}