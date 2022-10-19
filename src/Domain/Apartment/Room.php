<?php

namespace App\Domain\Apartment;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="apartment_rooms")
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
     * @ORM\Embedded(class="App\Domain\Apartment\SquareMeter")
     */
    private SquareMeter $squareMeter;

    /**
     * @ORM\ManyToOne(targetEntity="Apartment", inversedBy="rooms")
     * @ORM\JoinColumn(name="apartment_id", referencedColumnName="id", onDelete="CASCADE")
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