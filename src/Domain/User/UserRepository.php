<?php

namespace App\Domain\User;

interface UserRepository
{
    public function save(User $user): void;
}