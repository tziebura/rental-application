<?php

namespace App\Domain\HotelRoom;

use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * @ORM\Entity()
 */
class Room
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column()
     */
    private string $name;

    /**
     * @ORM\Embedded(class="App\Domain\HotelRoom\SquareMeter")
     */
    private SquareMeter $size;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\HotelRoom\HotelRoom", inversedBy="rooms")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private HotelRoom $hotelRoom;

    public function __construct(string $name, SquareMeter $size)
    {
        $this->name = $name;
        $this->size = $size;
    }

    public function assignToHotelRoom(HotelRoom $apartment)
    {
        if (isset($this->hotelRoom)) {
            throw new LogicException('Room cannot be reassigned');
        }

        $this->hotelRoom = $apartment;
    }
}