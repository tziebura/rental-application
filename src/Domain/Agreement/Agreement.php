<?php

namespace App\Domain\Agreement;

use App\Domain\Money\Money;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Agreement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column()
     */
    private string $rentalType;

    /**
     * @ORM\Column(type="integer")
     */
    private int $rentalPlaceId;

    /**
     * @ORM\Column()
     */
    private string $tenantId;

    /**
     * @ORM\Column()
     */
    private string $ownerId;

    /**
     * @ORM\Column(type="array")
     */
    private array $days;

    /**
     * @ORM\Embedded(class="App\Domain\Money\Money")
     */
    private Money $price;

    public function __construct(string $rentalType, int $rentalPlaceId, string $tenantId, string $ownerId, array $days, Money $price)
    {
        $this->id = null;
        $this->rentalType = $rentalType;
        $this->rentalPlaceId = $rentalPlaceId;
        $this->tenantId = $tenantId;
        $this->ownerId = $ownerId;
        $this->days = $days;
        $this->price = $price;
    }

    public function accept(AgreementEventsPublisher $publisher): void
    {
        $publisher->publishAgreementAccepted(
            $this->rentalType,
            $this->rentalPlaceId,
            $this->ownerId,
            $this->tenantId,
            $this->price->getValue(),
            $this->days
        );
    }

}