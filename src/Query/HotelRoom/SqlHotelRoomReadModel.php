<?php

namespace App\Query\HotelRoom;


use Doctrine\DBAL\Connection;

class SqlHotelRoomReadModel implements HotelRoomReadModel
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findByHotel(string $hotelId): array
    {
        $hotelRooms = [];

        $stmt = $this->connection->prepare('SELECT * FROM hotel_room WHERE hotel_id = :hotel_id');
        $result = $stmt->execute(['hotel_id' => $hotelId]);

        while($hotelRoom = $result->fetchAssociative()) {
            $stmt = $this->connection->prepare('SELECT * FROM room WHERE hotel_room_id = :id');
            $result = $stmt->execute(['id' => $hotelRoom['id']]);

            $hotelRoom['rooms'] = $result->fetchAllAssociative();
            $hotelRooms[] = HotelRoom::fromArray($hotelRoom);
        }

        return $hotelRooms;
    }
}