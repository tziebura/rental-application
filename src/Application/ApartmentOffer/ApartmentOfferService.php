<?php

namespace App\Application\ApartmentOffer;

use App\Domain\Apartment\ApartmentNotFoundException;
use App\Domain\Apartment\ApartmentRepository;
use App\Domain\ApartmentOffer\ApartmentOfferBuilder;
use App\Domain\ApartmentOffer\ApartmentOfferRepository;

class ApartmentOfferService
{
    private ApartmentOfferRepository $apartmentOfferRepository;
    private ApartmentRepository $apartmentRepository;

    public function __construct(ApartmentOfferRepository $apartmentOfferRepository, ApartmentRepository $apartmentRepository)
    {
        $this->apartmentOfferRepository = $apartmentOfferRepository;
        $this->apartmentRepository = $apartmentRepository;
    }

    public function add(ApartmentOfferDTO $dto)
    {
        if (!$this->apartmentRepository->existsById($dto->getApartmentId())) {
            throw ApartmentNotFoundException::withId($dto->getApartmentId());
        }

        $offer = ApartmentOfferBuilder::create()
            ->withApartmentId($dto->getApartmentId())
            ->withPrice($dto->getPrice())
            ->withAvailability($dto->getStart(), $dto->getEnd())
            ->build();

        $this->apartmentOfferRepository->save($offer);
    }
}