<?php

namespace App\Domain\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(indexes={@ORM\Index(name="login_idx", columns={"login"})})
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(unique=true)
     */
    private string $login;

    /**
     * @ORM\Embedded(class="Name")
     */
    private Name $name;

    public function __construct(string $login, Name $name)
    {
        $this->login = $login;
        $this->name = $name;
    }
}