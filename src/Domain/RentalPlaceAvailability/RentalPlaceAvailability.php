<?php

namespace App\Domain\RentalPlaceAvailability;

use App\Domain\Period\Period;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class RentalPlaceAvailability
{
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $start;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $end;

    private function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public static function of(DateTimeImmutable $start, DateTimeImmutable $end): self
    {
        $start = $start->setTime(0,0);
        $end = $end->setTime(0, 0);

        if ($start > $end) {
            throw RentalPlaceAvailabilityException::startAfterEnd($start, $end);
        }

        $today = (new DateTimeImmutable())->setTime(0, 0);

        if ($start < $today) {
            throw RentalPlaceAvailabilityException::startEarlierThanToday($start);
        }

        return new self($start, $end);
    }

    public function coversAllDaysWithin(Period $period)
    {
        return $period->isWithin($this->start, $this->end);
    }
}