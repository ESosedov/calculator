<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'id', type: 'bigint')]
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
