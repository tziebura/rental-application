<?php

namespace App\Application\Booking;

class RejectBooking
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}