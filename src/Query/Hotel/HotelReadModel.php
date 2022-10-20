<?php

namespace App\Query\Hotel;

interface HotelReadModel
{
    /**
     * @return Hotel[]
     */
    public function findAll(): array;
}