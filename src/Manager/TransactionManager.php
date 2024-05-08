<?php

declare(strict_types=1);

namespace App\Manager;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class TransactionManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function beginTransaction(): void
    {
        $this->entityManager->getConnection()->beginTransaction();
    }

    /**
     * @throws Exception
     */
    public function commit(): void
    {
        $this->entityManager->getConnection()->commit();
    }

    /**
     * @throws Exception
     */
    public function rollback(): void
    {
        $this->entityManager->getConnection()->rollBack();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function persist(object $object): void
    {
        $this->entityManager->persist($object);
    }

    public function refresh(object $object): void
    {
        $this->entityManager->refresh($object);
    }
}
