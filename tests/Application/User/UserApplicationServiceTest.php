<?php

namespace App\Tests\Application\User;

use App\Application\User\UserApplicationService;
use App\Application\User\UserDto;
use App\Domain\User\User;
use App\Domain\User\UserAlreadyExistsException;
use App\Domain\User\UserFactory;
use App\Domain\User\UserRepository;
use App\Tests\Domain\User\UserAssertion;
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
        $this->subject = new UserApplicationService($this->userRepository, new UserFactory($this->userRepository));
    }

    /**
     * @test
     */
    public function shouldRegisterNewUser(): void
    {
        $this->givenNonExistingUserWithLogin(self::LOGIN);
        $userDto = $this->givenUserDto();

        $this->thenUserShouldBeSaved();
        $this->subject->register($userDto);
    }

    /**
     * @test
     */
    public function shouldNotRegisterUserWhenUserWithGivenLoginAlreadyExists(): void
    {
        $this->givenExistingUserWithLogin(self::LOGIN);

        $this->expectException(UserAlreadyExistsException::class);

        $this->thenUserShouldNeverBeSaved();
        $this->subject->register($this->givenUserDto());
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

    private function givenNonExistingUserWithLogin(string $login): void
    {
        $this->userRepository->expects($this->once())
            ->method('existsWithLogin')
            ->with($login)
            ->willReturn(false);
    }

    /**
     * @return UserDto
     */
    public function givenUserDto(): UserDto
    {
        return new UserDto(
            self::LOGIN,
            self::NAME,
            self::LAST_NAME
        );
    }

    private function givenExistingUserWithLogin(string $login): void
    {
        $this->userRepository->expects($this->once())
            ->method('existsWithLogin')
            ->with($login)
            ->willReturn(true);
    }

    private function thenUserShouldNeverBeSaved(): void
    {
        $this->userRepository->expects($this->never())
            ->method('save');
    }
}