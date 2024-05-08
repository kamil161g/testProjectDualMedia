<?php

declare(strict_types=1);

namespace App\Factory\Order;

use App\DTO\Order\OrderInputDTO;
use App\Entity\Order;
use App\Enum\Order\OrderStatusEnum;
use DateTime;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class OrderFactory
{
    public function create(OrderInputDTO $orderInputDTO, float $totalPrice): Order
    {
        $order = new Order();
        $order->setOrderDate(new DateTime());
        $order->setCustomerEmail($orderInputDTO->getEmail());
        $order->setShippingAddress($orderInputDTO->getShippingAddress());
        $order->setTotalPrice($totalPrice);
        $order->setStatus(OrderStatusEnum::PENDING);

        return $order;
    }
}
