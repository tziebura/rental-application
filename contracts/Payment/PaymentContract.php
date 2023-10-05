<?php

namespace App\Contracts\Payment;

class PaymentContract
{
    /**
     * @return PaymentScenario[]
     */
    public function scenarios(): array
    {
        return [
            $this->successfulPayment(),
            $this->notEnoughMoney()
        ];
    }

    public function successfulPayment(): PaymentScenario
    {
        $request = new PaymentRequest("TestAccount1", "TestAccount2", 123.45);
        return new PaymentScenario($request, PaymentResponse::success());
    }

    public function notEnoughMoney(): PaymentScenario
    {
        $request = new PaymentRequest("TestAccountWithNoMoney", "TestAccount2", 123.45);
        return new PaymentScenario($request, PaymentResponse::notEnoughMoney());
    }
}