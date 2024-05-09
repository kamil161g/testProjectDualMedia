<?php

namespace spec\App\Handler\Order\Query;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Enum\Order\OrderStatusEnum;
use App\Exception\Order\OrderNotExistsException;
use App\Handler\Order\Query\ViewOrderQueryHandler;
use App\Model\Order\ProductToSummaryOrderModel;
use App\Model\Order\SummaryOrderModel;
use App\Repository\OrderItemsRepository;
use PhpSpec\ObjectBehavior;

class ViewOrderQueryHandlerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ViewOrderQueryHandler::class);
    }

    public function let(OrderItemsRepository $orderItemsRepository): void
    {
        $this->beConstructedWith($orderItemsRepository);
    }

    public function it_throws_order_not_exists_exception_if_order_does_not_exist(
        OrderItemsRepository $orderItemsRepository
    ): void {
        $orderId = 123;
        $orderItemsRepository->getByOrderId($orderId)->willReturn([]);

        $this->shouldThrow(OrderNotExistsException::class)->during('handle', [$orderId]);
    }

    public function it_returns_summary_order_model_if_order_exists(OrderItemsRepository $orderItemsRepository): void
    {
        $product = new Product();
        $product->setId(1);
        $product->setName('Test product');

        $order = new Order();
        $order->setId(1);
        $order->setTotalPrice(100);
        $order->setStatus(OrderStatusEnum::PENDING);
        $order->setShippingAddress('address');

        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setProduct($product);
        $orderItem->setQuantity(1);

        $orderItemsRepository->getByOrderId(1)->willReturn([$orderItem]);
        $productToSummary = new ProductToSummaryOrderModel(1, 1, 'Test product');

        $this->handle(1)->shouldBeLike(new SummaryOrderModel(
            1,
            100,
            'address',
            OrderStatusEnum::PENDING,
            [$productToSummary]
        ));
    }
}
