<?php

namespace App\Contracts\AddressVerification;

class AddressVerificationContract
{
    /**
     * @return AddressVerificationScenario[]
     */
    public function scenarios(): array
    {
        return [
            $this->validAddress(),
            $this->invalidAddress(),
        ];
    }

    public function validAddress(): AddressVerificationScenario
    {
        $request = new AddressVerificationRequest("Pawia", "1", "31-154", "Cracow", "Poland");
        $response = new AddressVerificationResponse("VALID");

        return new AddressVerificationScenario($request, $response);
    }

    public function invalidAddress(): AddressVerificationScenario
    {
        $request = new AddressVerificationRequest("Nowhere", "13", "12-345", "IDontKnow", "Somewhere");
        $response = new AddressVerificationResponse("INVALID");

        return new AddressVerificationScenario($request, $response);
    }
}