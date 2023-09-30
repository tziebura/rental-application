<?php

namespace App\Infrastructure\Persistence\Sql\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;

class SqlUserRepository implements UserRepository
{
    private DoctrineOrmUserRepository $doctrineOrmUserRepository;

    public function __construct(DoctrineOrmUserRepository $doctrineOrmUserRepository)
    {
        $this->doctrineOrmUserRepository = $doctrineOrmUserRepository;
    }

    public function save(User $user): void
    {
        $this->doctrineOrmUserRepository->save($user);
    }

    public function existsWithLogin(string $login): bool
    {
        return $this->doctrineOrmUserRepository->existsWithLogin($login);
    }
}