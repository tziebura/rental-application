<?php

namespace App\Domain\Apartment;

use App\Domain\Booking\Booking;
use App\Domain\EventChannel\EventChannel;
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
     * @ORM\Embedded(class="App\Domain\Apartment\Address")
     */
    private Address $address;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Apartment\Room", mappedBy="apartment", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $rooms;

    public function __construct(string $ownerId, Address $address, string $description, array $rooms)
    {
        $this->ownerId = $ownerId;
        $this->address = $address;
        $this->description = $description;
        $this->rooms = new ArrayCollection(array_map(function (Room $room) {
            $room->assignToApartment($this);
            return $room;
        }, $rooms));
    }

    public function book(string $tenantId, Period $period, ApartmentEventsPublisher $apartmentEventsPublisher): Booking
    {
        $apartmentEventsPublisher->publishApartmentBooked(
            $this->id,
            $this->ownerId,
            $tenantId,
            $period
        );

        return Booking::apartment(
            $this->id,
            $tenantId,
            $period
        );
    }
}