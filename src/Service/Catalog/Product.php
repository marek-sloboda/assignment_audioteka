<?php

namespace App\Service\Catalog;

use App\Entity\ProductDomain\ProductName;
use App\Entity\ProductDomain\ProductPrice;

interface Product
{
    public function getId(): string;
    public function getName(): string;
    public function getPrice(): int;
    public function getCreatedAtString(): string;
    public function setName(ProductName $productName): void; //TODO: implement and use ProductNameInterface
    public function setPrice(ProductPrice $ProductPrice): void; //TODO: implement and use ProductPriceInterface
}
