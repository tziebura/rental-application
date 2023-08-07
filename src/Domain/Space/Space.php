<?php

namespace App\Domain\Space;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
class Space
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @ORM\Column()
     */
    protected string $name;

    /**
     * @ORM\Embedded(class="App\Domain\Space\SquareMeter")
     */
    protected SquareMeter $size;

    public function __construct(string $name, SquareMeter $size)
    {
        $this->name = $name;
        $this->size = $size;
    }
}