<?php

namespace App\Domain\HotelRoom;

use App\Domain\Booking\Booking;
use App\Domain\EventChannel\EventChannel;
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
     * @ORM\Column()
     */
    private string $hotelId;

    /**
     * @ORM\Column()
     */
    private int $number;

    /**
     * @ORM\Column()
     */
    private string $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\HotelRoom\Room", mappedBy="hotelRoom", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $rooms;

    public function __construct(string $hotelId, int $number, string $description, array $rooms)
    {
        $this->hotelId = $hotelId;
        $this->number = $number;
        $this->description = $description;
        $this->rooms = new ArrayCollection(array_map(function (Space $room) {
            $room->assignToHotelRoom($this);
            return $room;
        }, $rooms));
    }

    public function book(array $days, string $tenantId, HotelRoomEventsPublisher $hotelRoomEventsPublisher): Booking
    {
        $hotelRoomEventsPublisher->publishHotelRoomBooked(
            $this->id,
            $this->hotelId,
            $days,
            $tenantId
        );

        return Booking::hotelRoom(
            $this->id,
            $tenantId,
            $days
        );
    }
}