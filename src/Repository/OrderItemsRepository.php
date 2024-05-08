<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PDO;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class OrderItemsRepository
{
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(OrderItem::class);
    }

    public function getByOrderId(int $orderId): array
    {
        $qb = $this->repository->createQueryBuilder('oi');
        $qb
            ->select()
            ->where('oi.order = :orderId')
            ->setParameter('orderId', $orderId, PDO::PARAM_INT);

        return $qb->getQuery()->getResult();
    }
}
