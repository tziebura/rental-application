<?php

namespace App\Domain\ApartmentOffer;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class ApartmentAvailability
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
            throw ApartmentAvailabilityException::startAfterEnd($start, $end);
        }

        return new self($start, $end);
    }
}