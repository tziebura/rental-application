<?php

namespace App\Tests\Domain\ApartmentOffer;

use App\Domain\ApartmentOffer\ApartmentOffer;
use App\Tests\PrivatePropertyManipulator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentOfferAssertion
{
    use PrivatePropertyManipulator;

    private ApartmentOffer $actual;

    public function __construct(ApartmentOffer $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(ApartmentOffer $actual): self
    {
        return new self($actual);
    }

    public function hasApartmentIdEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'apartmentId'));
        return $this;
    }

    public function hasPriceEqualTo(float $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'price'));
        return $this;
    }

    public function hasAvailabilityEqualTo(DateTimeImmutable $expectedStart, DateTimeImmutable $expectedEnd): self
    {
        $actualAvailability = $this->getByReflection($this->actual, 'availability');
        TestCase::assertEquals($expectedStart, $this->getByReflection($actualAvailability, 'start'));
        TestCase::assertEquals($expectedEnd, $this->getByReflection($actualAvailability, 'end'));
        return $this;
    }
}