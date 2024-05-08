<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PDO;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class ProductRepository
{
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Product::class);
    }

    public function getById(int $id): ?Product
    {
        $qb = $this->repository->createQueryBuilder('p');
        $qb
            ->select('p')
            ->where('p.id = :id')
            ->setParameter('id', $id, PDO::PARAM_INT);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
