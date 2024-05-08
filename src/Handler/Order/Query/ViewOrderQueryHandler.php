<?php

declare(strict_types=1);

namespace App\Handler\Order\Query;

use App\Model\Order\ProductToSummaryOrderModel;
use App\Model\Order\SummaryOrderModel;
use App\Repository\OrderItemsRepository;
use OrderNotExistsException;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class ViewOrderQueryHandler
{
    private OrderItemsRepository $orderItemsRepository;

    public function __construct(OrderItemsRepository $orderItemsRepository)
    {
        $this->orderItemsRepository = $orderItemsRepository;
    }

    /**
     * @param int $orderId
     *
     * @throws OrderNotExistsException
     * @return SummaryOrderModel
     */
    public function handle(int $orderId): SummaryOrderModel
    {
        $orderItem = $this->orderItemsRepository->getByOrderId($orderId);

        if (true === empty($orderItem)) {
            throw new OrderNotExistsException('Order not found');
        }
        $products = [];
        foreach ($orderItem as $item) {
            $product = $item->getProduct();
            $products[] = new ProductToSummaryOrderModel($product->getId(), $item->getQuantity(), $product->getName());
        }
        $order = $orderItem[0]->getOrder();

        return new SummaryOrderModel(
            $order->getId(),
            $order->getTotalPrice(),
            $order->getShippingAddress(),
            $order->getStatus(),
            $products
        );
    }
}
