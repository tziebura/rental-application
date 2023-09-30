<?php

namespace App\Application\User;

use App\Domain\User\UserFactory;
use App\Domain\User\UserRepository;

class UserApplicationService
{
    private UserRepository $userRepository;
    private UserFactory $userFactory;

    public function __construct(UserRepository $userRepository, UserFactory $userFactory)
    {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
    }

    public function register(UserDTO $userDto): void
    {
        $user = $this->userFactory->create(
            $userDto->getLogin(),
            $userDto->getFirstName(),
            $userDto->getLastName()
        );

        $this->userRepository->save($user);
    }
}