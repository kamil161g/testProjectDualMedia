<?php

declare(strict_types=1);

namespace App\Facade\Order;

use App\DTO\Order\OrderInputDTO;
use App\Factory\Order\OrderFactory;
use App\Factory\Order\OrderItemsFactory;
use App\Model\Order\ProductToSummaryOrderModel;
use App\Repository\ProductRepository;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class OrderProcessingFacade
{
    private ProductRepository $productRepository;
    private OrderFactory $orderFactory;
    private OrderItemsFactory $orderItemsFactory;

    public function __construct(
        ProductRepository $productRepository,
        OrderFactory $orderFactory,
        OrderItemsFactory $orderItemsFactory
    ) {
        $this->productRepository = $productRepository;
        $this->orderFactory = $orderFactory;
        $this->orderItemsFactory = $orderItemsFactory;
    }

    public function processOrder(OrderInputDTO $orderInputDTO): array
    {
        $productsToOrderData = [];
        $products = [];
        $totalPrice = 0.0;
        foreach ($orderInputDTO->getItems() as $item) {
            $product = $this->productRepository->getById($item->getProductId());
            $quantity = $item->getQuantity();
            $products[] = [
                'productId' => $product,
                'quantity' => $quantity,
            ];
            $totalPrice += $product->getPrice();
            $productsToOrderData[] = new ProductToSummaryOrderModel($product->getId(), $quantity, $product->getName());
        }

        $orderItems = [];
        $order = $this->orderFactory->create($orderInputDTO, $totalPrice);

        foreach ($products as $product) {
            $orderItems[] = $this->orderItemsFactory->create($product['productId'], $order, $product['quantity']);
        }

        return [
            'order' => $order,
            'orderItems' => $orderItems,
            'orderDataProducts' => $productsToOrderData
        ];
    }

}
