<?php

declare(strict_types=1);

namespace App\Manager;

use Psr\Log\LoggerInterface;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class LoggerManager
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function logError(object $exception, string $errorName = ''): void
    {
        $this->logger->error($errorName, [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'line' => $exception->getLine()
        ]);
    }
}
