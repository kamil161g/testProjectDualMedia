<?php

namespace spec\App\Handler\Order\Command;

use App\DTO\Order\OrderInputDTO;
use App\DTO\Order\OrderItemInputDTO;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Enum\Order\OrderStatusEnum;
use App\Exception\Order\CreateOrderException;
use App\Exception\Order\NotValidOrderException;
use App\Facade\Order\OrderProcessingFacade;
use App\Handler\Order\Command\CreateOrderCommandHandler;
use App\Manager\LoggerManager;
use App\Manager\TransactionManager;
use App\Model\Order\SummaryOrderModel;
use App\Support\DatabaseOperationSupport;
use App\Validator\Order\CreateOrderValidator;
use PhpSpec\ObjectBehavior;
use RuntimeException;

class CreateOrderCommandHandlerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CreateOrderCommandHandler::class);
    }

    public function let(
        CreateOrderValidator $createOrderValidator,
        DatabaseOperationSupport $databaseOperationSupport,
        OrderProcessingFacade $orderProcessingFacade
    ): void {
        $this->beConstructedWith($createOrderValidator, $databaseOperationSupport, $orderProcessingFacade);
    }

    public function it_throws_not_valid_order_exception_if_validation_fails(
        CreateOrderValidator $createOrderValidator,
        OrderInputDTO $orderInputDTO
    ): void {
        $createOrderValidator->isValid($orderInputDTO)->willReturn(false);
        $this->shouldThrow(NotValidOrderException::class)->during('handle', [$orderInputDTO]);
    }

    public function it_completes_transaction_and_returns_summary_if_valid(
        CreateOrderValidator $createOrderValidator,
        TransactionManager $transactionManager,
        OrderProcessingFacade $orderProcessingFacade,
        DatabaseOperationSupport $databaseOperationSupport
    ): void {
        $orderItemInputDTO = new OrderItemInputDTO(1, 5);
        $orderInputDTO = new OrderInputDTO('email@exmaple.com', 'address', [$orderItemInputDTO]);

        $order = new Order();
        $order->setId(1);
        $order->setTotalPrice(10);
        $order->setShippingAddress($orderInputDTO->getShippingAddress());
        $order->setStatus(OrderStatusEnum::PENDING);

        $product = new Product();
        $orderItem = new OrderItem();

        $createOrderValidator->isValid($orderInputDTO)->willReturn(true);

        $databaseOperationSupport->transactionManager()->willReturn($transactionManager);
        $transactionManager->beginTransaction()->shouldBeCalled();

        $orderProcessingFacadeReturnData = [
            'order' => $order,
            'orderItems' => [$orderItem],
            'orderDataProducts' => [$product],
        ];

        $orderProcessingFacade->processOrder($orderInputDTO)
            ->shouldBeCalled()
            ->willReturn($orderProcessingFacadeReturnData);

        $transactionManager->persist($order)->shouldBeCalled();
        $transactionManager->persist($orderItem)->shouldBeCalled();
        $transactionManager->flush()->shouldBeCalled();
        $transactionManager->commit()->shouldBeCalled();

        $summaryReturnOrderModel = new SummaryOrderModel(
            $order->getId(),
            $order->getTotalPrice(),
            $order->getShippingAddress(),
            $order->getStatus(),
            $orderProcessingFacadeReturnData['orderDataProducts']
        );

        $this->handle($orderInputDTO)->shouldBeLike($summaryReturnOrderModel);
    }

    public function it_rolls_back_and_throws_create_order_exception_on_failure(
        CreateOrderValidator $createOrderValidator,
        OrderInputDTO $orderInputDTO,
        DatabaseOperationSupport $databaseOperationSupport,
        TransactionManager $transactionManager,
        OrderProcessingFacade $orderProcessingFacade,
        LoggerManager $loggerManager
    ): void {
        $createOrderValidator->isValid($orderInputDTO)->willReturn(true);
        $databaseOperationSupport->transactionManager()->willReturn($transactionManager);
        $transactionManager->beginTransaction()->shouldBeCalled();

        $orderProcessingFacade->processOrder($orderInputDTO)->willThrow(new RuntimeException());

        $transactionManager->rollback()->shouldBeCalled();
        $databaseOperationSupport->logger()->willReturn($loggerManager);
        $loggerManager->logError(new RuntimeException(), 'CreateOrderHandler')->shouldBeCalled();

        $this->shouldThrow(CreateOrderException::class)->during('handle', [$orderInputDTO]);
    }
}
