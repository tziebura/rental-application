<?php

namespace App\Application\User;

use App\Domain\User\Name;
use App\Domain\User\User;
use App\Domain\User\UserAlreadyExistsException;
use App\Domain\User\UserBuilder;
use App\Domain\User\UserFactory;
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
        $user = (new UserFactory($this->userRepository))->create(
            $userDto->getLogin(),
            $userDto->getFirstName(),
            $userDto->getLastName()
        );

        $this->userRepository->save($user);
    }
}