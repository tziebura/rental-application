<?php

namespace App\Domain\Apartment;

use App\Domain\EventChannel\EventChannel;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
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
     * @ORM\OneToMany(targetEntity="App\Domain\Apartment\Room", mappedBy="apartment")
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

    public function book(string $tenantId, Period $period, EventChannel $eventChannel): Booking
    {
        $event = ApartmentBooked::create(
            $this->id,
            $this->ownerId,
            $tenantId,
            $period
        );

        $eventChannel->publish($event);

        return Booking::apartment(
            $this->id,
            $tenantId,
            $period
        );
    }
}