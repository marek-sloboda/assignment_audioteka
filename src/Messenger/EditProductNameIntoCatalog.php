<?php

declare(strict_types=1);

namespace App\Messenger;

class EditProductNameIntoCatalog
{
    public function __construct(public readonly string $productId, public readonly string $productName) {}
}
