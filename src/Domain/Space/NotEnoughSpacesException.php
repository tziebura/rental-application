<?php

namespace App\Domain\Space;

use InvalidArgumentException;

class NotEnoughSpacesException extends InvalidArgumentException
{
    public static function noSpaces(): self
    {
        return new self('No spaces provided.');
    }
}