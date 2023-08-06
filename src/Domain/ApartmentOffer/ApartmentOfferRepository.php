<?php

namespace App\Domain\ApartmentOffer;

interface ApartmentOfferRepository
{
    public function save(ApartmentOffer $apartmentOffer): void;
}