<?php

namespace App\Domain\ApartmentOffer;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="apartment_offers")
 */
class ApartmentOffer
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
    private string $apartmentId;

    /**
     * @ORM\Embedded(class="App\Domain\ApartmentOffer\Money")
     */
    private Money $price;

    /**
     * @ORM\Embedded(class="App\Domain\ApartmentOffer\ApartmentAvailability")
     */
    private ApartmentAvailability $availability;

    public function __construct(string $apartmentId, Money $price, ApartmentAvailability $availability)
    {
        $this->apartmentId = $apartmentId;
        $this->price = $price;
        $this->availability = $availability;
    }

}