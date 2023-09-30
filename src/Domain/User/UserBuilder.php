<?php

namespace App\Domain\User;

class UserBuilder
{

    private string $login;
    private Name $name;

    public static function user(): self
    {
        return new self();
    }

    public function withLogin(string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function withName(string $firstName, string $lastName): self
    {
        $this->name = new Name($firstName, $lastName);
        return $this;
    }

    public function build(): User
    {
        return new User(
            $this->login,
            $this->name
        );
    }
}