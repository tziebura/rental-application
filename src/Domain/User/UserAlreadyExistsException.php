<?php

namespace App\Domain\User;

use RuntimeException;

class UserAlreadyExistsException extends RuntimeException
{
    public static function withLogin(string $login): self
    {
        return new self(sprintf('User with login %s already exists.', $login));
    }
}