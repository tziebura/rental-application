<?php

namespace App\Domain\HotelRoom;

use App\Domain\EventChannel\EventChannel;
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
    private array $rooms;

    public function __construct(string $hotelId, int $number, string $description, array $rooms)
    {
        $this->hotelId = $hotelId;
        $this->number = $number;
        $this->description = $description;
        $this->rooms = array_map(function (Room $room) {
            $room->assignToHotelRoom($this);
        }, $rooms);
    }

    public function book(array $days, string $tenantId, EventChannel $eventChannel)
    {
        $event = HotelRoomBooked::create(
            $this->id,
            $this->hotelId,
            $days,
            $tenantId
        );

        $eventChannel->publish($event);
    }
}