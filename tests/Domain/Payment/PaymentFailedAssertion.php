<?php

namespace App\Tests\Domain\Payment;

use App\Domain\Payment\PaymentFailed;
use PHPUnit\Framework\TestCase;

class PaymentFailedAssertion
{
    private PaymentFailed $actual;

    public function __construct(PaymentFailed $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(PaymentFailed $actual): self
    {
        return new self($actual);
    }

    public function hasSenderId(string $expected): self
    {
        TestCase::assertEquals($expected, $this->actual->getSenderId());
        return $this;
    }

    public function hasRecipientId(string $expected): self
    {
        TestCase::assertEquals($expected, $this->actual->getRecipientId());
        return $this;
    }

    public function hasAmount(float $expected): self
    {
        TestCase::assertEquals($expected, $this->actual->getAmount());
        return $this;
    }
}