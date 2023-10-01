<?php

namespace App\Domain\ApartmentOffer;

use App\Domain\Apartment\ApartmentNotFoundException;
use App\Domain\Apartment\ApartmentRepository;
use App\Domain\Money\Money;
use App\Domain\RentalPlaceAvailability\RentalPlaceAvailability;
use DateTimeImmutable;

class ApartmentOfferFactory
{
    private ApartmentRepository $apartmentRepository;

    public function __construct(ApartmentRepository $apartmentRepository)
    {
        $this->apartmentRepository = $apartmentRepository;
    }

    public function create(string $apartmentId, float $price, DateTimeImmutable $start, DateTimeImmutable $end): ApartmentOffer
    {
        if (!$this->apartmentRepository->existsById($apartmentId)) {
            throw ApartmentNotFoundException::withId($apartmentId);
        }

        return new ApartmentOffer(
            $apartmentId,
            Money::of($price),
            RentalPlaceAvailability::of($start, $end)
        );
    }
}