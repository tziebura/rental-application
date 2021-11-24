<?php

namespace App\Domain\Apartment;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Room
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column()
     */
    private string $name;

    /**
     * @ORM\Embedded(class="SquareMeter")
     */
    private SquareMeter $squareMeter;

    /**
     * @ORM\ManyToOne(targetEntity="Apartment", inversedBy="rooms")
     * @ORM\JoinColumn(name="apartment_id", referencedColumnName="id")
     */
    private Apartment $apartment;

    public function __construct(string $name, SquareMeter $squareMeter)
    {
        $this->name = $name;
        $this->squareMeter = $squareMeter;
    }

    public function assignToApartment(Apartment $apartment)
    {
        if (isset($this->apartment)) {
            throw new \LogicException('Room cannot be reassigned');
        }

        $this->apartment = $apartment;
    }
}