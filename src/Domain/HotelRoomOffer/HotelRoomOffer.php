<?php

namespace App\Domain\HotelRoomOffer;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="hotel_room_offers")
 */
class HotelRoomOffer
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
    private string $hotelRoomId;

    /**
     * @ORM\Embedded(class="App\Domain\HotelRoomOffer\Money")
     */
    private Money $price;

    /**
     * @ORM\Embedded(class="App\Domain\HotelRoomOffer\HotelRoomAvailability")
     */
    private HotelRoomAvailability $availability;

    public function __construct(string $hotelRoomId, Money $price, HotelRoomAvailability $availability)
    {
        $this->hotelRoomId = $hotelRoomId;
        $this->price = $price;
        $this->availability = $availability;
    }

}