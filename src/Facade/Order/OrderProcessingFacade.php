<?php

declare(strict_types=1);

namespace App\Facade\Order;

use App\Calculator\PricingCalculator;
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
    private PricingCalculator $pricingCalculator;

    public function __construct(
        ProductRepository $productRepository,
        OrderFactory $orderFactory,
        OrderItemsFactory $orderItemsFactory,
        PricingCalculator $pricingCalculator
    ) {
        $this->productRepository = $productRepository;
        $this->orderFactory = $orderFactory;
        $this->orderItemsFactory = $orderItemsFactory;
        $this->pricingCalculator = $pricingCalculator;
    }

    public function processOrder(OrderInputDTO $orderInputDTO): array
    {
        $productsToOrderData = [];
        $products = [];

        foreach ($orderInputDTO->getItems() as $item) {
            $product = $this->productRepository->getById($item->getProductId());
            $quantity = $item->getQuantity();
            $products[] = [
                'productId' => $product,
                'quantity' => $quantity,
            ];
            $productsToOrderData[] = new ProductToSummaryOrderModel($product->getId(), $quantity, $product->getName());
        }
        $pricingData = $this->pricingCalculator->calculateOrder($products);


        $orderItems = [];
        $order = $this->orderFactory->create($orderInputDTO, $pricingData['total']);

        foreach ($products as $product) {
            $orderItems[] = $this->orderItemsFactory->create($product['productId'], $order, $product['quantity']);
        }

        return [
            'order' => $order,
            'orderItems' => $orderItems,
            'orderDataProducts' => $productsToOrderData,
            'pricingData' => $pricingData
        ];
    }
}
