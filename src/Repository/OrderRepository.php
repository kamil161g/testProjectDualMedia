<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PDO;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class OrderRepository
{
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Order::class);
    }

    public function getById(int $orderId): ?Order
    {
        $qb = $this->repository->createQueryBuilder('o');
        $qb
            ->select()
            ->where('o.id = :id')
            ->setParameter('id', $orderId, PDO::PARAM_INT);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
