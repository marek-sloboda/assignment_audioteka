<?php

namespace App\Repository;

use App\Entity\CartProducts;
use App\Entity\Product;
use App\Service\Cart\Cart;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class CartRepository implements CartService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function addProduct(string $cartId, string $productId): void
    {
        $cart = $this->entityManager->find(\App\Entity\Cart::class, $cartId);
        $product = $this->entityManager->find(Product::class, $productId);
        $cartProduct = $this->entityManager->getRepository(CartProducts::class)->findOneBy(['cart' => $cart, 'product' => $product]);

        if ($cartProduct) {
            $cartProduct->increaseProductQuantity();
            $this->entityManager->flush();
        }

        if(!$cartProduct){
            $cartProduct = new CartProducts($cart, $product);
            $this->entityManager->persist($cartProduct);
            $this->entityManager->flush();
        }
    }

    public function removeProduct(string $cartId, string $productId): void
    {
        $cart = $this->entityManager->find(\App\Entity\Cart::class, $cartId);
        $product = $this->entityManager->find(Product::class, $productId);
        $cartProduct = $this->entityManager->getRepository(CartProducts::class)->findOneBy(['cart' => $cart, 'product' => $product]);

        if ($cartProduct && $cartProduct->getProductQuantity() === 1) {
           // $cartProduct->removeProduct($product);
            $this->entityManager->remove($cartProduct);
            $this->entityManager->flush();
        }

        if ($cartProduct && $cartProduct->getProductQuantity() >= 1) {
            $cartProduct->decreaseProductQuantity();
            $this->entityManager->flush();
        }
    }

    public function create(): Cart
    {
        $cart = new \App\Entity\Cart(Uuid::uuid4()->toString());

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $cart;
    }
}
