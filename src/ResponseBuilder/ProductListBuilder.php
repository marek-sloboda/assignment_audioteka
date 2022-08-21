<?php

namespace App\ResponseBuilder;

use App\Service\Catalog\Product;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductListBuilder
{
    public function __construct(private UrlGeneratorInterface $urlGenerator) { }

    /**
     * @param Product[] $products
     */
    public function __invoke(iterable $products, int $page, int $maxPerPage, int $totalCount): array
    {
        $data = [
            'previous_page' => null,
            'next_page' => null,
            'count' => $totalCount,
            'products' => []
        ];

        if ($page > 0) {
            $data['previous_page'] = $this->urlGenerator->generate('product-list', ['page' => $page - 1]);
        }

        $lastPage = ceil($totalCount / $maxPerPage);
        if ($page < $lastPage - 1) {
            $data['next_page'] = $this->urlGenerator->generate('product-list', ['page' => $page + 1]);
        }

        foreach ($products as $product) {
            $data['products'][] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
           //     'createdAt' => $product->getCreatedAtString(), TODO: if uncomment, must fix tests for ex.\App\Tests\Unit\ResponseBuilder\CartBuilderTest::test_builds_cart_with_products
            ];
        }

        return $data;
    }
}
