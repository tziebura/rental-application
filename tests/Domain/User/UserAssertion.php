<?php

namespace App\Tests\Domain\User;

use App\Domain\User\User;
use App\Domain\User\UserBuilder;
use App\Tests\PrivatePropertyManipulator;
use PHPUnit\Framework\TestCase;

class UserAssertion
{
    use PrivatePropertyManipulator;

    private User $actual;

    private function __construct(User $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(User $actual): self
    {
        return new self($actual);
    }

    public function represents(string $login, string $firstName, string $lastName): self
    {
        $expected = UserBuilder::user()
            ->withLogin($login)
            ->withName($firstName, $lastName)
            ->build();

        TestCase::assertEquals($expected, $this->actual);

        return $this;
    }
}