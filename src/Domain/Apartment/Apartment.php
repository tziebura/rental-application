<?php

namespace App\Domain\Apartment;

use App\Domain\Address\Address;
use App\Domain\Booking\Booking;
use App\Domain\Money\Money;
use App\Domain\Period\Period;
use App\Domain\Space\NotEnoughSpacesException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="apartments")
 */
class Apartment
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
    private string $ownerId;

    /**
     * @ORM\Embedded(class="App\Domain\Address\Address")
     */
    private Address $address;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @ORM\Column(name="address_apartment_number")
     */
    private string $apartmentNumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Apartment\Room", mappedBy="apartment", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $rooms;

    public function __construct(string $ownerId, string $apartmentNumber, Address $address, string $description, array $rooms)
    {
        $this->ownerId = $ownerId;
        $this->apartmentNumber = $apartmentNumber;
        $this->address = $address;
        $this->description = $description;

        if (empty($rooms)) {
            throw NotEnoughSpacesException::noSpaces();
        }

        $this->rooms = new ArrayCollection(array_map(function (Room $room) {
            $room->assignToApartment($this);
            return $room;
        }, $rooms));
    }

    public function book(ApartmentBooking $booking): Booking
    {
        $booking->getApartmentEventsPublisher()->publishApartmentBooked(
            $this->id,
            $this->ownerId,
            $booking->getTenantId(),
            $booking->getPeriod()
        );

        return Booking::apartment(
            $this->id,
            $booking->getTenantId(),
            $booking->getPeriod(),
            $this->ownerId,
            $booking->getPrice()
        );
    }
}