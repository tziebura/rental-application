<?php

namespace App\Domain\Agreement;

interface AgreementRepository
{
    public function save(Agreement $agreement): void;
    public function findById(int $id): ?Agreement;
}