<?php

namespace App\Application\User;

class UserDTO
{
    private string $login;
    private string $firstName;
    private string $lastName;

    public function __construct(string $login, string $firstName, string $lastName)
    {
        $this->login = $login;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}