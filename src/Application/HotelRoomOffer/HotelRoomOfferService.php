<?php

namespace App\Application\HotelRoomOffer;

use App\Domain\HotelRoomOffer\HotelRoomAvailability;
use App\Domain\HotelRoomOffer\HotelRoomOffer;
use App\Domain\HotelRoomOffer\HotelRoomOfferBuilder;
use App\Domain\HotelRoomOffer\HotelRoomOfferRepository;
use App\Domain\HotelRoomOffer\Money;
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
        $hotelRoomOffer = HotelRoomOfferBuilder::create()
            ->withHotelRoomId($hotelRoomId)
            ->withPrice($price)
            ->withAvailability($start, $end)
            ->build();
        $hotelRoomOffer = new HotelRoomOffer(
            $hotelRoomId,
            new Money($price),
            new HotelRoomAvailability($start, $end)
        );

        $this->hotelRoomOfferRepository->save($hotelRoomOffer);
    }

}