<?php

namespace App\Query\Apartment;

use DateTimeImmutable;

class ApartmentBooking
{
    private const DB_DATE_FORMAT = 'Y-m-d H:i:s';

    private int $id;
    private string $step;
    private DateTimeImmutable $bookingDateTime;
    private string $ownerId;
    private string $tenantId;
    private DateTimeImmutable $bookingStart;
    private DateTimeImmutable $bookingEnd;

    public function __construct(int $id, string $step, DateTimeImmutable $bookingDateTime, string $ownerId, string $tenantId, DateTimeImmutable $bookingStart, DateTimeImmutable $bookingEnd)
    {
        $this->id = $id;
        $this->step = $step;
        $this->bookingDateTime = $bookingDateTime;
        $this->ownerId = $ownerId;
        $this->tenantId = $tenantId;
        $this->bookingStart = $bookingStart;
        $this->bookingEnd = $bookingEnd;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['id'],
            $data['step'],
            DateTimeImmutable::createFromFormat(self::DB_DATE_FORMAT, $data['booking_date_time']),
            $data['owner_id'],
            $data['tenant_id'],
            DateTimeImmutable::createFromFormat(self::DB_DATE_FORMAT, $data['booking_period_start']),
            DateTimeImmutable::createFromFormat(self::DB_DATE_FORMAT, $data['booking_period_end']),
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStep(): string
    {
        return $this->step;
    }

    public function getBookingDateTime(): DateTimeImmutable
    {
        return $this->bookingDateTime;
    }

    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    public function getBookingStart(): DateTimeImmutable
    {
        return $this->bookingStart;
    }

    public function getBookingEnd(): DateTimeImmutable
    {
        return $this->bookingEnd;
    }
}