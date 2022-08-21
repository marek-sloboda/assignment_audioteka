<?php

namespace App\Messenger;

use App\Service\Catalog\ProductService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddProductToCatalogHandler implements MessageHandlerInterface
{
    public function __construct(private readonly ProductService $service) { }

    public function __invoke(AddProductToCatalog $command): void
    {
        $this->service->add($command->name, $command->price);
    }
}
