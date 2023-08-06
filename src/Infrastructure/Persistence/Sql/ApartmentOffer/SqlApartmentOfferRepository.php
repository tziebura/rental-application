<?php

namespace App\Infrastructure\Persistence\Sql\ApartmentOffer;

use App\Domain\ApartmentOffer\ApartmentOffer;
use App\Domain\ApartmentOffer\ApartmentOfferRepository;

class SqlApartmentOfferRepository implements ApartmentOfferRepository
{
    private DoctrineOrmApartmentOfferRepository $repository;

    public function __construct(DoctrineOrmApartmentOfferRepository $repository)
    {
        $this->repository = $repository;
    }

    public function save(ApartmentOffer $apartmentOffer): void
    {
        $this->repository->save($apartmentOffer);
    }
}