<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Entity\ProductDomain\ProductName;
use App\Service\Catalog\ProductService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EditProductNameIntoCatalogHandler implements MessageHandlerInterface
{
    public function __construct(private readonly ProductService $service)
    {
    }

    public function __invoke(EditProductNameIntoCatalog $command): void
    {
        $this->service->editName($command->productId, new ProductName($command->productName));
    }
}
