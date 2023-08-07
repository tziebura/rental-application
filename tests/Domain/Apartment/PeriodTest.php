<?php

namespace App\Tests\Domain\Apartment;

use App\Domain\Period\Period;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PeriodTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnAllDaysBetweenStartAndEnd()
    {
        $start = new DateTimeImmutable('01-01-2022');
        $end = new DateTimeImmutable('03-01-2022');

        $actual = Period::of($start, $end);

        $this->assertEquals(
            [
                $start,
                new DateTimeImmutable('02-01-2022'),
                $end,
            ],
            $actual->asDays()
        );
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenStartIsGreaterThanEnd()
    {
        $start = new DateTimeImmutable('03-01-2022');
        $end = new DateTimeImmutable('01-01-2022');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Start cannot be greater than end.');

        Period::of($start, $end);
    }
}
