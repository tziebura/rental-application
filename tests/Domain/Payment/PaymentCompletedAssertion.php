<?php

namespace App\Tests\Domain\Payment;

use App\Domain\Payment\PaymentCompleted;
use PHPUnit\Framework\TestCase;

class PaymentCompletedAssertion
{
    private PaymentCompleted $actual;

    public function __construct(PaymentCompleted $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(PaymentCompleted $actual): self
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