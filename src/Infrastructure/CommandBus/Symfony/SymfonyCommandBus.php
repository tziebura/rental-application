<?php

namespace App\Infrastructure\Symfony\CommandBus;

use App\Application\CommandBus\CommandBus;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SymfonyCommandBus implements CommandBus
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function dispatch($command): void
    {
        $this->eventDispatcher->dispatch($command, get_class($command));
    }
}