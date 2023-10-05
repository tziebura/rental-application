<?php

namespace App\Application\Agreement;

use App\Domain\Agreement\AgreementEventsPublisher;
use App\Domain\Agreement\AgreementRepository;

class AgreementApplicationService
{
    private AgreementRepository $agreementRepository;
    private AgreementEventsPublisher $publisher;

    public function __construct(AgreementRepository $agreementRepository, AgreementEventsPublisher $publisher)
    {
        $this->agreementRepository = $agreementRepository;
        $this->publisher = $publisher;
    }

    public function accept(int $agreementId): void
    {
        $agreement = $this->agreementRepository->findById($agreementId);
        $agreement->accept($this->publisher);
    }
}