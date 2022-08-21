<?php

namespace App\Entity;

use App\Entity\ProductDomain\ProductName;
use App\Entity\ProductDomain\ProductPrice;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class Product implements \App\Service\Catalog\Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $priceAmount;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Timestampable(on: 'update')]
    private DateTime $updatedAt;

    public function __construct(string $id, string $name, int $price)
    {
        $this->id = Uuid::fromString($id);
        $this->name = (new ProductName($name))->getName();
        $this->priceAmount = (new ProductPrice($price))->getPrice();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function setName(ProductName $productName): void
    {
        $this->name = $productName->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPrice(ProductPrice $productPrice): void
    {
        $this->priceAmount = $productPrice->getPrice();
    }

    public function getPrice(): int
    {
        return $this->priceAmount;
    }

    public function getCreatedAtString(): string
    {
        return $this->createdAt->format('Y-m-d h:i:s');
    }
}
