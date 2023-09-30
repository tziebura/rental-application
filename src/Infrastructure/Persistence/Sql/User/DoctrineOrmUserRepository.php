<?php

namespace App\Infrastructure\Persistence\Sql\User;

use App\Domain\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOrmUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function existsWithLogin(string $login): bool
    {
        return $this->count(['login' => $login]) > 0;
    }
}