<?php

namespace App\Infrastructure\AddressService;

use App\Domain\Address\AddressCatalogue;
use App\Domain\Address\AddressDto;

class RestAddressCatalogueClient implements AddressCatalogue
{

    public function exists(AddressDto $addressDto): bool
    {
        return true;
    }
}