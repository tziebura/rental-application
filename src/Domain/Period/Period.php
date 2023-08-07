<?php

namespace App\Domain\Period;

use DateTimeImmutable;
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
        if ($start > $end) {
            throw new InvalidArgumentException('Start cannot be greater than end.');
        }

        $this->start = $start;
        $this->end = $end;
    }

    public static function of(DateTimeImmutable $start, DateTimeImmutable $end): Period
    {
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
     * @return DateTimeImmutable[]
     */
    public function asDays(): array
    {
        $start = $this->start;
        $days  = [$start];

        while ($start < $this->end) {
            $start = $start->modify('+1day');
            $days[] = $start;
        }

        return $days;
    }
}