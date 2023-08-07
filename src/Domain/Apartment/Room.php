<?php

namespace App\Domain\Apartment;

use App\Domain\Space\Space;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="apartment_rooms")
 */
class Room extends Space
{
    /**
     * @ORM\ManyToOne(targetEntity="Apartment", inversedBy="rooms")
     * @ORM\JoinColumn(name="apartment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private Apartment $apartment;

    public function assignToApartment(Apartment $apartment)
    {
        $this->apartment = $apartment;
    }
}