<?php

namespace App\Domain\EventChannel;

interface EventChannel
{
    public function publish($event): void;
}