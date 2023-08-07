<?php

namespace App\Domain\Apartment;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Address
{
    /**
     * @ORM\Column()
     */
    private string $street;

    /**
     * @ORM\Column()
     */
    private string $postalCode;

    /**
     * @ORM\Column()
     */
    private string $houseNumber;

    /**
     * @ORM\Column()
     */
    private string $city;

    /**
     * @ORM\Column()
     */
    private string $country;

    public function __construct(string $street, string $postalCode, string $houseNumber, string $city, string $country)
    {
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->houseNumber = $houseNumber;
        $this->city = $city;
        $this->country = $country;
    }
}