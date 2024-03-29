<?php

namespace App\Domain\Period;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Embeddable()
 */
class Period
{
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $start;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public static function of(DateTimeImmutable $start, DateTimeImmutable $end): Period
    {
        $now = (new DateTimeImmutable())->setTime(0, 0);

        if ($start < $now) {
            throw PeriodException::startDateFromPast($start);
        }

        if ($start > $end) {
            throw PeriodException::startDateAfterEndDate($start, $end);
        }

        return new self(
            $start,
            $end
        );
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): DateTimeImmutable
    {
        return $this->end;
    }

    /**
     * @return string[]
     */
    public function asDays(): array
    {
        $start = $this->start;
        $days  = [$start->format('Y-m-d')];

        while ($start < $this->end) {
            $start = $start->modify('+1day');
            $days[] = $start->format('Y-m-d');
        }

        return $days;
    }

    public function contains(DateTimeInterface $date): bool
    {
        $days = $this->asDays();

        foreach ($days as $day) {
            $dateString = $date->format('Y-m-d');

            if ($day === $dateString) {
                return true;
            }
        }

        return false;
    }

    public function isWithin(DateTimeImmutable $start, DateTimeImmutable $end): bool
    {
        return $this->start >= $start && $this->end <= $end;
    }
}