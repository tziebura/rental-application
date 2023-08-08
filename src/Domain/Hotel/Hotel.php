<?php

namespace App\Domain\Hotel;

use App\Domain\Address\Address;
use App\Domain\Booking\Booking;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Hotel
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
    private string $name;

    /**
     * @ORM\Embedded(class="App\Domain\Address\Address")
     */
    private Address $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Hotel\HotelRoom", mappedBy="hotel", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $rooms;

    public function __construct(string $name, Address $address)
    {
        $this->name = $name;
        $this->address = $address;
        $this->rooms = new ArrayCollection();
    }

    public function addHotelRoom(int $number, string $description, array $rooms)
    {
        $hotelRoom = HotelRoomBuilder::create()
            ->withHotel($this)
            ->withNumber($number)
            ->withDescription($description)
            ->withRooms($rooms)
            ->build();

        $this->rooms->add($hotelRoom);
    }

    public function bookRoom(int $roomNumber, string $tenantId, array $days, HotelEventsPublisher $hotelEventsPublisher): Booking
    {
        $room = $this->getRoomWithNumber($roomNumber);

        return $room->book($days, $tenantId, $hotelEventsPublisher);
    }

    public function hasRoomWithNumber(int $number): bool
    {
        $room = $this->getRoomWithNumber($number);
        return $room !== false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $roomNumber
     * @return HotelRoom|false
     */
    private function getRoomWithNumber(int $roomNumber)
    {
        return $this->rooms->filter(fn(HotelRoom $room) => $room->getNumber() === $roomNumber)
            ->first();
    }
}