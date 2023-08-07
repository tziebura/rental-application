<?php

namespace App\Domain\HotelRoom;

use App\Domain\Space\Space;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Room extends Space
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\HotelRoom\HotelRoom", inversedBy="rooms")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private HotelRoom $hotelRoom;

    public function assignToHotelRoom(HotelRoom $apartment)
    {
        $this->hotelRoom = $apartment;
    }
}