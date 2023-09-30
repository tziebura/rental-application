<?php

namespace App\Tests\Application\User;

use App\Application\User\UserApplicationService;
use App\Application\User\UserDto;
use App\Domain\User\User;
use App\Domain\User\UserAssertion;
use App\Domain\User\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserApplicationServiceTest extends TestCase
{
    private const LOGIN = 'tziebura';
    private const NAME = 'Tomasz';
    private const LAST_NAME = 'Ziebura';
    private UserApplicationService $subject;
    private UserRepository $userRepository;
    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->subject = new UserApplicationService($this->userRepository);
    }

    /**
     * @test
     */
    public function shouldRegisterNewUser(): void
    {
        $userDto = new UserDto(
            self::LOGIN,
            self::NAME,
            self::LAST_NAME
        );

        $this->thenUserShouldBeSaved();
        $this->subject->register($userDto);
    }

    private function thenUserShouldBeSaved(): void
    {
        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (User $user) {
                UserAssertion::assertThat($user)->represents(
                    self::LOGIN,
                    self::NAME,
                    self::LAST_NAME
                );
                return true;
            }));
    }

}