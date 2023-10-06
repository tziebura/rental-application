<?php

namespace App\Contracts\AddressVerification;

class AddressVerificationScenario
{
    private AddressVerificationRequest $request;
    private AddressVerificationResponse $response;

    public function __construct(AddressVerificationRequest $request, AddressVerificationResponse $response)
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

    public function getRequest(): AddressVerificationRequest
    {
        return $this->request;
    }

    public function getResponse(): AddressVerificationResponse
    {
        return $this->response;
    }
}