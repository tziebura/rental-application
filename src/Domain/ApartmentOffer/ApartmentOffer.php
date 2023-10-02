<?php

namespace App\Domain\ApartmentOffer;

use App\Domain\Money\Money;
use App\Domain\Period\Period;
use App\Domain\RentalPlaceAvailability\RentalPlaceAvailability;
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
     * @ORM\Embedded(class="App\Domain\Money\Money")
     */
    private Money $price;

    /**
     * @ORM\Embedded(class="App\Domain\RentalPlaceAvailability\RentalPlaceAvailability")
     */
    private RentalPlaceAvailability $availability;

    public function __construct(string $apartmentId, Money $price, RentalPlaceAvailability $availability)
    {
        $this->apartmentId = $apartmentId;
        $this->price = $price;
        $this->availability = $availability;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function hasAvailabilityWithin(Period $period): bool
    {
        return $this->availability->coversAllDaysWithin($period);
    }
}