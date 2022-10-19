<?php

namespace App\Query\Apartment;

use Doctrine\DBAL\Connection;

class SqlApartmentReadModel implements ApartmentReadModel
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM apartments');
        $result = $stmt->executeQuery();

        $apartments = [];
        while ($apartment = $result->fetchAssociative()) {
            $apartments[] = $this->mapApartmentFromArray($apartment);
        }

        return $apartments;
    }

    public function findById(string $id): ?ApartmentDetails
    {
        $stmt = $this->connection->prepare('SELECT * FROM apartments WHERE id = :apartment_id LIMIT 1');
        $result = $stmt->executeQuery(['apartment_id' => $id]);
        $apartment = $result->fetchAssociative();

        if (empty($apartment)) {
            return null;
        }

        $stmt = $this->connection->prepare('SELECT * FROM apartment_booking_histories WHERE apartment_id = :apartment_id LIMIT 1');
        $result = $stmt->executeQuery(['apartment_id' => $id]);
        $history = $result->fetchAssociative();

        if (!$history) {
            return new ApartmentDetails($this->mapApartmentFromArray($apartment));
        }

        $stmt = $this->connection->prepare('SELECT * FROM apartment_bookings WHERE apartment_booking_history_id = :apartment_id');
        $result = $stmt->executeQuery(['apartment_id' => $id]);
        $history['bookings'] = $result->fetchAllAssociative();

        return new ApartmentDetails(
            $this->mapApartmentFromArray($apartment),
            ApartmentBookingHistory::fromArray($history)
        );
    }

    private function mapApartmentFromArray(array $apartment): Apartment
    {
        $stmt = $this->connection->prepare('SELECT * FROM apartment_rooms WHERE apartment_id = :apartment_id');
        $roomQueryResult = $stmt->executeQuery(['apartment_id' => $apartment['id']]);
        $apartment['rooms'] = $roomQueryResult->fetchAllAssociative();
        return Apartment::fromArray($apartment);
    }
}