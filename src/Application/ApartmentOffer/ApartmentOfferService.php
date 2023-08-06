<?php

namespace App\Application\ApartmentOffer;

use App\Domain\Apartment\ApartmentNotFoundException;
use App\Domain\Apartment\ApartmentRepository;
use App\Domain\ApartmentOffer\ApartmentOfferBuilder;
use App\Domain\ApartmentOffer\ApartmentOfferRepository;
use DateTimeImmutable;

class ApartmentOfferService
{
    private ApartmentOfferRepository $apartmentOfferRepository;
    private ApartmentRepository $apartmentRepository;

    public function __construct(ApartmentOfferRepository $apartmentOfferRepository, ApartmentRepository $apartmentRepository)
    {
        $this->apartmentOfferRepository = $apartmentOfferRepository;
        $this->apartmentRepository = $apartmentRepository;
    }

    public function add(string $apartmentId, float $price, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if (!$this->apartmentRepository->existsById($apartmentId)) {
            throw new ApartmentNotFoundException(sprintf('Apartment with ID %s does not exist', $apartmentId));
        }

        $offer = ApartmentOfferBuilder::create()
            ->withApartmentId($apartmentId)
            ->withPrice($price)
            ->withAvailability($start, $end)
            ->build();

        $this->apartmentOfferRepository->save($offer);
    }
}