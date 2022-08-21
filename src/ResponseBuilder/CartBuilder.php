<?php

namespace App\ResponseBuilder;

use App\Entity\CartProducts;
use App\Service\Cart\Cart;

class CartBuilder
{
    public function __invoke(Cart $cart): array
    {
        $data = [
            'total_price' => $cart->getTotalPrice(),
            'products' => []
        ];

        /** @var CartProducts $cartProduct */
        foreach ($cart->getProducts() as $cartProduct) {
            $data['products'][] = [
                'id' => $cartProduct->getProduct()->getId(),
                'name' => $cartProduct->getProduct()->getName(),
                'price' => $cartProduct->getProduct()->getPrice()
            ];
        }

        return $data;
    }
}
