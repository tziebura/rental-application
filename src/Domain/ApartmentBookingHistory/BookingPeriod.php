<?php

namespace App\Domain\ApartmentBookingHistory;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Embeddable()
 */
class BookingPeriod
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
            throw new InvalidArgumentException('Start cannot be greater than end');
        }

        $this->start = $start;
        $this->end = $end;
    }

}