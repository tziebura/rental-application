<?php

namespace App\Domain\Apartment;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Apartment
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column()
     */
    private string $ownerId;

    /**
     * @ORM\Embedded(class = "Address")
     */
    private Address $address;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="apartment")
     */
    private array $rooms;

    public function __construct(string $ownerId, Address $address, string $description, array $rooms)
    {
        $this->ownerId = $ownerId;
        $this->address = $address;
        $this->description = $description;
        $this->rooms = array_map(function (Room $room) {
            $room->assignToApartment($this);
            return $room;
        }, $rooms);
    }
}