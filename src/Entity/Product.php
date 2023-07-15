<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products', options: ['comment' => 'Товар'])]
class Product extends AbstractEntity
{
    #[ORM\Column(type: 'string', length: 255, nullable: false, options: ['comment' => 'Наименование товара'])]
    private ?string $title;

    #[ORM\Column(type: 'integer', nullable: false, options: ['comment' => 'Стоимость товара, евроцент'])]
    private ?int $cost;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }
}
