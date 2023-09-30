<?php

namespace App\Application\User;

use App\Domain\User\Name;
use App\Domain\User\User;
use App\Domain\User\UserBuilder;
use App\Domain\User\UserRepository;

class UserApplicationService
{
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserDto $userDto): void
    {
        $user = UserBuilder::user()
            ->withLogin($userDto->getLogin())
            ->withName($userDto->getFirstName(), $userDto->getLastName())
            ->build();

        $this->userRepository->save($user);
    }
}