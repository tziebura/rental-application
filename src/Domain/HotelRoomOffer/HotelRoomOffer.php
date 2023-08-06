<?php

namespace App\Domain\HotelRoomOffer;

class HotelRoomOffer
{
    private int $id;
    private string $hotelRoomId;
    private Money $price;
    private HotelRoomAvailability $availability;

    public function __construct(string $hotelRoomId, Money $price, HotelRoomAvailability $availability)
    {
        $this->hotelRoomId = $hotelRoomId;
        $this->price = $price;
        $this->availability = $availability;
    }

}