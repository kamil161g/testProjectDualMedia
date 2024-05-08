<?php

declare(strict_types=1);

namespace App\Handler\Order\Command;

use App\DTO\Order\OrderInputDTO;
use App\Facade\Order\OrderProcessingFacade;
use App\Model\Order\SummaryOrderModel;
use App\Support\DatabaseOperationSupport;
use App\Validator\Order\CreateOrderValidator;
use CreateOrderException;
use Doctrine\DBAL\Exception;
use NotValidOrderException;
use Throwable;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class CreateOrderCommandHandler
{
    private CreateOrderValidator $createOrderValidator;
    private DatabaseOperationSupport $databaseOperationSupport;
    private OrderProcessingFacade $orderProcessingFacade;

    public function __construct(
        CreateOrderValidator $createOrderValidator,
        DatabaseOperationSupport $databaseOperationSupport,
        OrderProcessingFacade $orderProcessingFacade
    ) {
        $this->createOrderValidator = $createOrderValidator;
        $this->databaseOperationSupport = $databaseOperationSupport;
        $this->orderProcessingFacade = $orderProcessingFacade;
    }

    /**
     * @param OrderInputDTO $orderInputDTO
     *
     * @throws CreateOrderException
     * @throws Exception
     * @throws NotValidOrderException
     * @return SummaryOrderModel
     */
    public function handle(OrderInputDTO $orderInputDTO): SummaryOrderModel
    {
        if ($this->createOrderValidator->isValid($orderInputDTO) === false) {
            throw new NotValidOrderException('Not valid');
        }

        $transactionManager = $this->databaseOperationSupport->transactionManager();
        $transactionManager->beginTransaction();

        try {
            $orderData = $this->orderProcessingFacade->processOrder($orderInputDTO);

            $order = $orderData['order'];
            $transactionManager->persist($order);

            foreach ($orderData['orderItems'] as $orderItem) {
                $transactionManager->persist($orderItem);
            }
            $transactionManager->flush();
            $transactionManager->commit();

            return new SummaryOrderModel(
                $order->getId(),
                $order->getTotalPrice(),
                $order->getShippingAddress(),
                $order->getStatus(),
                $orderData['orderDataProducts']);

        } catch (Throwable $exception) {
            $transactionManager->rollback();
            $this->databaseOperationSupport->logger()->logError($exception, 'CreateOrderHandler');
            throw new CreateOrderException($exception->getMessage());
        }
    }
}
