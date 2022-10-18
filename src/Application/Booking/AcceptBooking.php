<?php

namespace App\Application\Booking;

class AcceptBooking
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}