<?php

namespace App\Domain\HotelRoomOffer;

interface HotelRoomOfferRepository
{
    public function save(HotelRoomOffer $offer): void;
}