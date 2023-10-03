<?php

namespace App\Domain\Hotel;

use App\Domain\Booking\Booking;
use App\Domain\Money\Money;
use App\Domain\Space\Space;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class HotelRoom
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Hotel\Hotel", inversedBy="rooms")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Hotel $hotel;

    /**
     * @ORM\Column()
     */
    private int $number;

    /**
     * @ORM\Column()
     */
    private string $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Hotel\Room", mappedBy="hotelRoom", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $rooms;

    public function __construct(Hotel $hotel, int $number, string $description, array $rooms)
    {
        $this->hotel = $hotel;
        $this->number = $number;
        $this->description = $description;
        $this->rooms = new ArrayCollection(array_map(function (Space $room) {
            $room->assignToHotelRoom($this);
            return $room;
        }, $rooms));
    }

    public function book(array $days, string $tenantId, HotelEventsPublisher $hotelRoomEventsPublisher): Booking
    {
        $hotelRoomEventsPublisher->publishHotelRoomBooked(
            $this->id,
            $this->hotel->getId(),
            $days,
            $tenantId
        );

        return Booking::hotelRoom(
            $this->id,
            $tenantId,
            $days,
            'ownerId',
            Money::of(100.0)
        );
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}