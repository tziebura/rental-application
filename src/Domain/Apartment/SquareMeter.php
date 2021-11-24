<?php

namespace App\Domain\Apartment;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class SquareMeter
{
    /**
     * @ORM\Column(type="float")
     */
    private float $size;

    public function __construct(float $size)
    {
        $this->size = $size;
    }
}