<?php

namespace App\Application\Hotel;

use App\Domain\Hotel\HotelFactory;
use App\Domain\Hotel\HotelRepository;

class HotelApplicationService
{
    private HotelRepository $hotelRepository;

    public function __construct(HotelRepository $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository;
    }

    public function add(
        string $name, string $street, string $buildingNumber, string $postalCode, string $city, string $country
    ): void {
        $factory = new HotelFactory();
        $hotel = $factory->create(
            $name, $street, $buildingNumber, $postalCode, $city, $country);

        $this->hotelRepository->save($hotel);
    }
}