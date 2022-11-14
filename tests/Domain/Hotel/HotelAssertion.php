<?php

namespace App\Tests\Domain\Hotel;

use App\Domain\Hotel\Address;
use App\Domain\Hotel\Hotel;
use App\Tests\PrivatePropertyManipulator;
use PHPUnit\Framework\TestCase;

class HotelAssertion
{
    use PrivatePropertyManipulator;

    private Hotel $actual;

    public function __construct(Hotel $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(Hotel $actual): self
    {
        return new self($actual);
    }

    public function hasNameEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'name'));
        return $this;
    }

    public function hasAddressEqualTo(Address $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'address'));
        return $this;
    }
}