<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Controller\JsonController;
use App\Handler\Order\Query\ViewOrderQueryHandler;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class ViewOrderController extends JsonController
{
    private ViewOrderQueryHandler $viewOrderQueryHandler;

    public function __construct(
        SerializerInterface $serializer,
        LoggerInterface $logger,
        ViewOrderQueryHandler $viewOrderQueryHandler
    ) {
        parent::__construct($serializer, $logger);

        $this->viewOrderQueryHandler = $viewOrderQueryHandler;
    }

    public function getOrder(int $orderId): JsonResponse
    {
        try {
            return $this->getSuccessResponse($this->viewOrderQueryHandler->handle($orderId));
        } catch (Throwable $exception) {
            return $this->getErrorResponse($exception);
        }
    }
}
