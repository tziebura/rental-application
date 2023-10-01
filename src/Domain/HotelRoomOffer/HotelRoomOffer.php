<?php

namespace App\Domain\HotelRoomOffer;

use App\Domain\Money\Money;
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
    private string $hotelId;

    /**
     * @ORM\Column(type="integer")
     */
    private int $hotelRoomNumber;

    /**
     * @ORM\Embedded(class="App\Domain\Money\Money")
     */
    private Money $price;

    /**
     * @ORM\Embedded(class="App\Domain\HotelRoomOffer\HotelRoomAvailability")
     */
    private HotelRoomAvailability $availability;

    public function __construct(string $hotelId, int $hotelRoomNumber, Money $price, HotelRoomAvailability $availability)
    {
        $this->hotelId = $hotelId;
        $this->hotelRoomNumber = $hotelRoomNumber;
        $this->price = $price;
        $this->availability = $availability;
    }

}