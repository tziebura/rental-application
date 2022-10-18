<?php

namespace App\Application\CommandBus;

interface CommandBus
{
    public function dispatch($command): void;
}