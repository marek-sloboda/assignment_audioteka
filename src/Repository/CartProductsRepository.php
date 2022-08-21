<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CartProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartProducts>
 *
 * @method CartProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartProducts[]    findAll()
 * @method CartProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartProducts::class);
    }

    public function add(CartProducts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CartProducts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
