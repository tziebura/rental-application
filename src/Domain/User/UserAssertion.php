<?php

namespace App\Domain\User;

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
        $expected = new User($login, new Name($firstName, $lastName));
        TestCase::assertEquals($expected, $this->actual);

        return $this;
    }
}