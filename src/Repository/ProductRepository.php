<?php

namespace App\Repository;

use App\Entity\ProductDomain\ProductName;
use App\Entity\ProductDomain\ProductPrice;
use App\Service\Catalog\Product;
use App\Service\Catalog\ProductProvider;
use App\Service\Catalog\ProductService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductRepository implements ProductProvider, ProductService
{
    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly ValidatorInterface $validator)
    {
        $this->repository = $this->entityManager->getRepository(\App\Entity\Product::class);
    }

    public function getProducts(int $page = 0, int $count = 3): iterable
    {
        return $this->repository->createQueryBuilder('p')
            ->setMaxResults($count)
            ->setFirstResult($page * $count)
            ->orderBy('p.createdAt','DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getTotalCount(): int
    {
        return $this->repository->createQueryBuilder('p')->select('count(p.id)')->getQuery()->getSingleScalarResult();
    }

    public function exists(string $productId): bool
    {
        return $this->repository->find($productId) !== null;
    }

    /**
     * @throws Exception
     */
    public function add(string $name, string $price): Product
    {
        $product = new \App\Entity\Product(Uuid::uuid4(), $name, $price);

        $errors = $this->validator->validate($product);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
           throw new Exception($errorsString);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function remove(string $id): void
    {
        $product = $this->repository->find($id);
        if ($product !== null) {
            $this->entityManager->remove($product);
            $this->entityManager->flush();
        }
    }

    public function editName(string $productId, ProductName $productName): Product
    {
        $product = $this->repository->find($productId);

        if ($product !== null) {
            $product->setName($productName);
            $this->entityManager->flush();
        }

        return $product;
    }

    public function editPrice(string $productId, ProductPrice $productPrice): Product
    {
        $product = $this->repository->find($productId);

        if ($product !== null) {
            $product->setPrice($productPrice);
            $this->entityManager->flush();
        }

        return $product;
    }
}
