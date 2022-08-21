<?php

declare(strict_types=1);

namespace App\Messenger;

class EditProductPriceIntoCatalog
{
    public function __construct(public readonly string $productId, public readonly int $productPrice) {}
}
