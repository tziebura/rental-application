<?php

namespace App\Query\Hotel;

use Doctrine\DBAL\Connection;

class SqlHotelReadModel implements HotelReadModel
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(): array
    {
        $stmt = $this->connection->prepare('SELECT *, (SELECT COUNT(*) FROM hotel_room WHERE hotel_id = h.id) number_of_rooms FROM hotel AS h');
        $result = $stmt->executeQuery();

        $hotels = [];

        while($hotel = $result->fetchAssociative()) {
            $hotels[] = Hotel::fromArray($hotel);
        }

        return $hotels;
    }
}