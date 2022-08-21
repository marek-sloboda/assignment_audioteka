<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Entity\ProductDomain\ProductName;
use App\Entity\ProductDomain\ProductPrice;
use App\Service\Catalog\ProductService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EditProductPriceIntoCatalogHandler implements MessageHandlerInterface
{
    public function __construct(private readonly ProductService $service)
    {
    }

    public function __invoke(EditProductPriceIntoCatalog $command): void
    {
        $this->service->editPrice($command->productId, new ProductPrice($command->productPrice));
    }
}
