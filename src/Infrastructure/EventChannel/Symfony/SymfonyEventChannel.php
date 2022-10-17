<?php

namespace App\Infrastructure\EventChannel;

use App\Domain\EventChannel\EventChannel;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SymfonyEventChannel implements EventChannel
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function publish($event): void
    {
        $this->eventDispatcher->dispatch($event, get_class($event));
    }
}