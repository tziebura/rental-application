<?php

namespace App\Domain\Hotel;

interface HotelRepository
{
    public function save(Hotel $hotel): void;
}