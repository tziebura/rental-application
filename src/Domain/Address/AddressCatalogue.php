<?php

namespace App\Domain\Address;

interface AddressCatalogue
{
    public function exists(AddressDto $addressDto): bool;
}