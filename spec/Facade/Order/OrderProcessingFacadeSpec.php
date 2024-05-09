<?php

namespace spec\App\Facade\Order;

use App\Calculator\PricingCalculator;
use App\DTO\Order\OrderInputDTO;
use App\DTO\Order\OrderItemInputDTO;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Facade\Order\OrderProcessingFacade;
use App\Factory\Order\OrderFactory;
use App\Factory\Order\OrderItemsFactory;
use App\Model\Order\ProductToSummaryOrderModel;
use App\Repository\ProductRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OrderProcessingFacadeSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderProcessingFacade::class);
    }

    public function let(
        ProductRepository $productRepository,
        OrderFactory $orderFactory,
        OrderItemsFactory $orderItemsFactory,
        PricingCalculator $pricingCalculator
    ): void {
        $this->beConstructedWith($productRepository, $orderFactory, $orderItemsFactory, $pricingCalculator);
    }

    public function it_processes_orders_correctly(
        ProductRepository $productRepository,
        OrderFactory $orderFactory,
        OrderItemsFactory $orderItemsFactory,
        PricingCalculator $pricingCalculator,
        Product $product,
        Order $order
    ): void {
        $item1 = new OrderItemInputDTO(1, 5);
        $orderInputDTO = new OrderInputDTO('email@exmaple.com', 'address', [$item1]);

        $product->getId()->willReturn(1);
        $product->getName()->willReturn('Product Name');

        $productRepository->getById(1)->willReturn($product);

        $productsToOrderData = new ProductToSummaryOrderModel(1, 5, 'Product Name');

        $pricingCalculator->calculateOrder(Argument::type('array'))->willReturn([
            'total' => 1000,
            'subtotal' => 800,
            'vat' => 200
        ]);

        $orderFactory->create($orderInputDTO, 1000)->willReturn($order);

        $orderItem = new OrderItem();
        $orderItemsFactory->create($product, $order, 5)->willReturn($orderItem);

        $this->processOrder($orderInputDTO)->shouldBeLike([
            'order' => $order,
            'orderItems' => [$orderItem],
            'orderDataProducts' => [$productsToOrderData],
            'pricingData' => ['subtotal' => 800, 'vat' => 200, 'total' => 1000]
        ]);
    }
}
