<?php

namespace App\Infrastructure\Persistence\Sql\Owner;

use App\Domain\Owner\OwnerRepository;
use App\Domain\User\User;
use Doctrine\DBAL\Connection;

class SqlOwnerRepository implements OwnerRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function exists(string $id): bool
    {
        $stmt = $this->connection->prepare('SELECT COUNT(id) FROM user WHERE id = :id');
        $result = $stmt->executeQuery(['id' => $id]);
        $count = (int) $result->fetchOne();

        return $count > 0;
    }
}