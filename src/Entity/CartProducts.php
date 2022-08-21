<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CartProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
//TODO: refactor name from plural to singular CartProduct - now it's confusing
#[ORM\Entity(repositoryClass: CartProductsRepository::class)]
class CartProducts
{
    public const DEFAULT_QUANTITY = 1;

    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Cart $cart;

    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Product $product;

    #[ORM\Column(name:'quantity')]
    private int $productQuantity = self::DEFAULT_QUANTITY;


    public function __construct(Cart $cart, Product $product)
    {
        $this->cart = $cart;
        $this->product = $product;
    }

    public function increaseProductQuantity(): self
    {
        $this->setProductQuantity($this->getProductQuantity() + 1);

        return $this;
    }

    public function decreaseProductQuantity(): self
    {
        $this->setProductQuantity($this->getProductQuantity() - 1);

        return $this;
    }

    public function getProductQuantity(): int
    {
        return $this->productQuantity;
    }

    public function setProductQuantity(int $ProductQuantity): void
    {
        $this->productQuantity = $ProductQuantity;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}
