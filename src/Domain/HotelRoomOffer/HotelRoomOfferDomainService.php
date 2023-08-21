<?php

namespace App\Domain\HotelRoomOffer;

use App\Domain\Hotel\Hotel;
use App\Domain\Hotel\HotelRoomNotFoundException;

class HotelRoomOfferDomainService
{
    /**
     * @param Hotel $hotel
     * @param CreateHotelRoomOffer $createHotelRoomOffer
     * @return HotelRoomOffer
     */
    public function createHotelRoomOffer(Hotel $hotel, CreateHotelRoomOffer $createHotelRoomOffer): HotelRoomOffer
    {
        if (!$hotel->hasRoomWithNumber($createHotelRoomOffer->getHotelRoomNumber())) {
            throw HotelRoomNotFoundException::withNumber($createHotelRoomOffer->getHotelRoomNumber());
        }

        return HotelRoomOfferBuilder::create()
            ->withHotelId($hotel->getId())
            ->withHotelRoomNumber($createHotelRoomOffer->getHotelRoomNumber())
            ->withPrice($createHotelRoomOffer->getPrice())
            ->withAvailability($createHotelRoomOffer->getStart(), $createHotelRoomOffer->getEnd())
            ->build();
    }
}