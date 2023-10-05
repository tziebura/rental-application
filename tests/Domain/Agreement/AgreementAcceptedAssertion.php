<?php

namespace App\Tests\Domain\Agreement;

use App\Domain\Agreement\AgreementAccepted;
use App\Tests\PrivatePropertyManipulator;
use PHPUnit\Framework\TestCase;

class AgreementAcceptedAssertion
{
    use PrivatePropertyManipulator;

    private AgreementAccepted $actual;

    public function __construct(AgreementAccepted $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(AgreementAccepted $actual): self
    {
        return new self($actual);
    }

    public function hasRentalType(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'rentalType'));
        return $this;
    }

    public function hasRentalPlaceId(int $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'rentalPlaceId'));
        return $this;
    }

    public function hasOwnerId(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'ownerId'));
        return $this;
    }

    public function hasTenantId(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'tenantId'));
        return $this;
    }

    public function hasPrice(float $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'price'));
        return $this;
    }

    public function hasDays(array $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'days'));
        return $this;
    }
}