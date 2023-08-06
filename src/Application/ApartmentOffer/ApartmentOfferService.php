<?php

namespace App\Application\ApartmentOffer;

use App\Domain\ApartmentOffer\ApartmentAvailability;
use App\Domain\ApartmentOffer\ApartmentOffer;
use App\Domain\ApartmentOffer\ApartmentOfferRepository;
use App\Domain\ApartmentOffer\Money;
use DateTimeImmutable;

class ApartmentOfferService
{
    private ApartmentOfferRepository $apartmentOfferRepository;

    public function __construct(ApartmentOfferRepository $apartmentOfferRepository)
    {
        $this->apartmentOfferRepository = $apartmentOfferRepository;
    }

    public function add(string $apartmentId, float $price, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $offer = new ApartmentOffer(
            $apartmentId,
            new Money($price),
            new ApartmentAvailability($start, $end)
        );

        $this->apartmentOfferRepository->save($offer);
    }
}