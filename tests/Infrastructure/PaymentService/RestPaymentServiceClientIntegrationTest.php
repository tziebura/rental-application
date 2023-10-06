<?php

namespace App\Tests\Infrastructure\PaymentService;

use App\Contracts\Payment\PaymentContract;
use App\Infrastructure\PaymentService\RestPaymentServiceClient;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class RestPaymentServiceClientIntegrationTest extends WebTestCase
{
    private RestPaymentServiceClient $subject;
    private PaymentContract $contract;

    public function setUp(): void
    {
        $this->bootKernel();
        $this->subject = $this->getContainer()->get(RestPaymentServiceClient::class);
        $this->contract = new PaymentContract();
    }

    /**
     * @test
     */
    public function shouldRecognizePaymentSuccess(): void
    {
        $scenario = $this->contract->successfulPayment();
        $request = $scenario->getRequest();

        $actual = $this->subject->transfer($request->getSenderId(), $request->getRecipientId(), $request->getAmount());
        $this->assertEquals($actual, $scenario->getResponse()->getStatus());
    }

    /**
     * @test
     */
    public function shouldRecognizeNotEnoughMoney(): void
    {
        $scenario = $this->contract->notEnoughMoney();
        $request = $scenario->getRequest();

        $actual = $this->subject->transfer($request->getSenderId(), $request->getRecipientId(), $request->getAmount());
        $this->assertEquals($actual, $scenario->getResponse()->getStatus());
    }
}