<?php

namespace spec\App\Factory\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Factory\Order\OrderItemsFactory;
use PhpSpec\ObjectBehavior;

class OrderItemsFactorySpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderItemsFactory::class);
    }

    public function it_creates_an_order_item_from_product_order_and_quantity(): void
    {
        $quantity = 5;
        $product = new Product();
        $order = new Order();

        $orderItem = $this->create($product, $order, $quantity);
        $orderItem->shouldBeAnInstanceOf(OrderItem::class);
        $orderItem->getProduct()->shouldReturn($product);
        $orderItem->getOrder()->shouldReturn($order);
        $orderItem->getQuantity()->shouldReturn($quantity);
    }
}
