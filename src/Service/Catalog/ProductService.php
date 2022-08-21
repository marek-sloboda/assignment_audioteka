<?php

declare(strict_types=1);

namespace App\Service\Catalog;

use App\Entity\ProductDomain\ProductName;
use App\Entity\ProductDomain\ProductPrice;

interface ProductService
{
    public function add(string $name, string $price): Product;

    public function editName(string $productId, ProductName $productName): Product;

    public function editPrice(string $productId, ProductPrice $productPrice): Product;

    public function remove(string $id): void;
}
