<?php

namespace App\Domain\User;

class User
{
    private string $login;
    private Name $name;

    public function __construct(string $login, Name $name)
    {
        $this->login = $login;
        $this->name = $name;
    }
}