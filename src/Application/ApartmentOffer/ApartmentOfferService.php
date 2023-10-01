<?php

namespace App\Application\ApartmentOffer;

use App\Domain\ApartmentOffer\ApartmentOfferFactory;
use App\Domain\ApartmentOffer\ApartmentOfferRepository;

class ApartmentOfferService
{
    private ApartmentOfferRepository $apartmentOfferRepository;
    private ApartmentOfferFactory $apartmentOfferFactory;

    public function __construct(ApartmentOfferRepository $apartmentOfferRepository, ApartmentOfferFactory $apartmentOfferFactory)
    {
        $this->apartmentOfferRepository = $apartmentOfferRepository;
        $this->apartmentOfferFactory = $apartmentOfferFactory;
    }

    public function add(ApartmentOfferDTO $dto)
    {
        $offer = $this->apartmentOfferFactory->create(
            $dto->getApartmentId(),
            $dto->getPrice(),
            $dto->getStart(),
            $dto->getEnd()
        );

        $this->apartmentOfferRepository->save($offer);
    }
}