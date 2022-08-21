<?php

namespace App\Entity;

use App\Service\Catalog\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class Cart implements \App\Service\Cart\Cart
{
    public const CAPACITY = 3;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: 'App\Entity\CartProducts')]
    #[ORM\JoinTable(name: 'cart_products')]
    private ?Collection $products = null;

    public function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
        $this->products = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getTotalPrice(): int
    {
        $totalPrice = 0;

        /** @var CartProducts $cartProduct */
        foreach ($this->products as $cartProduct){
            $totalPrice += ($cartProduct->getProduct()->getPrice() * $cartProduct->getProductQuantity());
       }

        return $totalPrice;
    }

    #[Pure]
    public function isFull(): bool
    {
        return $this->getTotalQuantity() >= self::CAPACITY;
    }

    public function getProducts(): iterable
    {
        return $this->products->getIterator();
    }

    #[Pure]
    public function hasProduct(\App\Entity\Product $product): bool
    {
        return $this->products->contains($product);
    }


    public function addCartProduct(\App\Entity\CartProducts $cartProduct): void
    {
        $this->products->add($cartProduct);
    }

    public function removeProduct(\App\Entity\Product $product): void
    {
        $this->products->removeElement($product);
    }

    private function getTotalQuantity(): int
    {
        $totalQuantity = 0;

        /** @var CartProducts $cartProduct */
        foreach ($this->products as $cartProduct){
            $totalQuantity += $cartProduct->getProductQuantity();
        }

        return $totalQuantity;
    }
}
