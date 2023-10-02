<?php

namespace App\Domain\Agreement;

interface AgreementRepository
{
    public function save(Agreement $agreement): void;
}