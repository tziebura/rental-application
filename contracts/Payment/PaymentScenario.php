<?php

namespace App\Contracts\Payment;

class PaymentScenario
{
    private PaymentRequest $request;
    private PaymentResponse $response;

    public function __construct(PaymentRequest $request, PaymentResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function getRequestAsJson(): string
    {
        return $this->request->serialize();
    }

    public function getResponseAsJson(): string
    {
        return $this->response->serialize();
    }
}