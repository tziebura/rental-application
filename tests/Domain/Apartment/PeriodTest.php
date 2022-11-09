<?php

namespace App\Tests\Domain\Apartment;

use App\Domain\Apartment\Period;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PeriodTest extends TestCase
{
    /**
     * @test
     */
    public function testShouldReturnAllDaysBetweenStartAndEnd()
    {
        $start = new DateTimeImmutable('01-01-2022');
        $end = new DateTimeImmutable('03-01-2022');

        $actual = new Period($start, $end);

        $this->assertEquals(
            [
                $start,
                new DateTimeImmutable('02-01-2022'),
                $end,
            ],
            $actual->asDays()
        );
    }
}
