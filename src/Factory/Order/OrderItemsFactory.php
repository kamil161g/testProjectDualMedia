<?php

declare(strict_types=1);

namespace App\Factory\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class OrderItemsFactory
{
    public function create(Product $product, Order $order, int $quantity): OrderItem
    {
        $orderItem = new OrderItem();
        $orderItem->setProduct($product);
        $orderItem->setQuantity($quantity);
        $orderItem->setOrder($order);

        return $orderItem;
    }
}
