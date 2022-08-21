<?php

declare(strict_types=1);

namespace App\Entity\ProductDomain;

class ProductPrice
{
    public function __construct(private null | string | int $price = null)
    {
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}
