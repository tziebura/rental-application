<?php

namespace App\Domain\User;

class UserFactory
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(string $login, string $firstName, string $lastName): User
    {
        if ($this->userRepository->existsWithLogin($login)) {
            throw UserAlreadyExistsException::withLogin($login);
        }

        return UserBuilder::user()
            ->withLogin($login)
            ->withName($firstName, $lastName)
            ->build();
    }
}