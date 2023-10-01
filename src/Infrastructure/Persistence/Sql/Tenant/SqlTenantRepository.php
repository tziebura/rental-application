<?php

namespace App\Infrastructure\Persistence\Sql\Tenant;

use App\Domain\Tenant\TenantRepository;
use Doctrine\DBAL\Connection;

class SqlTenantRepository implements TenantRepository
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