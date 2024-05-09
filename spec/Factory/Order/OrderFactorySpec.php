<?php

namespace spec\App\Factory\Order;

use App\DTO\Order\OrderInputDTO;
use App\Entity\Order;
use App\Enum\Order\OrderStatusEnum;
use App\Factory\Order\OrderFactory;
use DateTime;
use PhpSpec\ObjectBehavior;

class OrderFactorySpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderFactory::class);
    }

    public function it_creates_order_from_order_input_dto_and_total_price(): void
    {
        $email = 'email@example.com';
        $shippingAddress = 'address';
        $totalPrice = 100.00;

        $orderInputDTO = new OrderInputDTO($email, $shippingAddress, []);

        $order = $this->create($orderInputDTO, $totalPrice);
        $order->shouldBeAnInstanceOf(Order::class);
        $order->getCustomerEmail()->shouldReturn($email);
        $order->getShippingAddress()->shouldReturn($shippingAddress);
        $order->getTotalPrice()->shouldReturn($totalPrice);
        $order->getOrderDate()->shouldHaveType(DateTime::class);
        $order->getStatus()->shouldReturn(OrderStatusEnum::PENDING);
    }
}
