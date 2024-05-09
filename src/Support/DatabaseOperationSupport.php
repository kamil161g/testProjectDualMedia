<?php

declare(strict_types=1);

namespace App\Support;

use App\Manager\LoggerManager;
use App\Manager\TransactionManager;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class DatabaseOperationSupport
{
    private TransactionManager $transactionManager;

    private LoggerManager $loggerManager;

    public function __construct(TransactionManager $transactionManager, LoggerManager $loggerManager)
    {
        $this->transactionManager = $transactionManager;
        $this->loggerManager = $loggerManager;
    }

    public function transactionManager(): TransactionManager
    {
        return $this->transactionManager;
    }

    public function logger(): LoggerManager
    {
        return $this->loggerManager;
    }
}
