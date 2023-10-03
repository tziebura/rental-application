<?php

namespace App\Tests\Domain\Apartment;

use App\Domain\Period\Period;
use App\Domain\Period\PeriodException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PeriodTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnAllDaysBetweenStartAndEnd()
    {
        $start = new DateTimeImmutable();
        $end = $start->modify('+2days');

        $actual = Period::of($start, $end);

        $this->assertEquals(
            [
                $start->format('Y-m-d'),
                $start->modify('+1days')->format('Y-m-d'),
                $end->format('Y-m-d'),
            ],
            $actual->asDays()
        );
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenStartIsGreaterThanEnd()
    {
        $start = new DateTimeImmutable();
        $end = $start->modify('+2days');

        $this->expectException(PeriodException::class);
        $this->expectExceptionMessage(sprintf(
            'Start date: %s of period is after end date: %s.',
            $end->format('Y-m-d'),
            $start->format('Y-m-d')
        ));

        Period::of($end, $start);
    }
}
