<?php

namespace App\Domain\Hotel;

use App\Domain\Address\Address;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Hotel
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
     * @ORM\Embedded(class="App\Domain\Address\Address")
     */
    private Address $address;

    public function __construct(string $name, Address $address)
    {
        $this->name = $name;
        $this->address = $address;
    }
}