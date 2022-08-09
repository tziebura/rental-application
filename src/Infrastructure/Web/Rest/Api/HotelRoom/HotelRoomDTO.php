<?php

namespace App\Infrastructure\Web\Rest\Api\HotelRoom;

use Symfony\Component\Validator\Constraints as Assert;

class HotelRoomDTO
{
    /**
     * @Assert\NotBlank()
     */
    private string $hotelId;

    /**
     * @Assert\NotBlank()
     */
    private int $number;

    /**
     * @Assert\NotBlank()
     */
    private string $description;

    /**
     * @var array<string, float>
     */
    private array $rooms;

    public function __construct(string $hotelId, int $number, string $description, array $rooms)
    {
        $this->hotelId = $hotelId;
        $this->number = $number;
        $this->description = $description;
        $this->rooms = $rooms;
    }

    public function getHotelId(): string
    {
        return $this->hotelId;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getRooms(): array
    {
        return $this->rooms;
    }
}