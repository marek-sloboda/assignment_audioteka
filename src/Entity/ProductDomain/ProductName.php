<?php

declare(strict_types=1);

namespace App\Entity\ProductDomain;

class ProductName
{
    public function __construct(private null|string $name = null)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

}
