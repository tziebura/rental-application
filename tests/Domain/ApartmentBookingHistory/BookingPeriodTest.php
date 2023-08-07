<?php

namespace App\Tests\Domain\ApartmentBookingHistory;

use App\Domain\Period\Period;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingPeriodTest extends TestCase
{
    public function testShouldThrowExceptionWhenStartIsGreaterThanEnd()
    {
        $start = new DateTimeImmutable('03-01-2022');
        $end = new DateTimeImmutable('01-01-2022');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Start cannot be greater than end.');

        Period::of($start, $end);
    }
}