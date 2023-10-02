<?php

namespace App\Tests\Domain\Agreement;

use App\Domain\Agreement\Agreement;
use App\Domain\Money\Money;
use App\Tests\PrivatePropertyManipulator;
use PHPUnit\Framework\TestCase;

class AgreementAssertion
{
    use PrivatePropertyManipulator;

    private Agreement $actual;

    public function __construct(Agreement $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(Agreement $actual): self
    {
        return new self($actual);
    }

    public function hasRentalTypeEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'rentalType'));
        return $this;
    }

    public function hasRentalPlaceIdEqualTo(int $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'rentalPlaceId'));
        return $this;
    }

    public function hasOwnerIdEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'ownerId'));
        return $this;
    }

    public function hasTenantIdEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'tenantId'));
        return $this;
    }

    public function hasDaysEqualTo(array $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'days'));
        return $this;
    }

    public function hasPriceEqualTo(Money $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'price'));
        return $this;
    }
}