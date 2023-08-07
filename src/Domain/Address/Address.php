<?php

namespace App\Domain\Address;

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
    private string $buildingNumber;

    /**
     * @ORM\Column()
     */
    private string $postalCode;

    /**
     * @ORM\Column()
     */
    private string $city;

    /**
     * @ORM\Column()
     */
    private string $country;

    public function __construct(string $street, string $buildingNumber, string $postalCode, string $city, string $country)
    {
        $this->street = $street;
        $this->buildingNumber = $buildingNumber;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->country = $country;
    }


}