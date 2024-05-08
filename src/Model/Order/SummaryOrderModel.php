<?php

declare(strict_types=1);

namespace App\Model\Order;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
readonly class SummaryOrderModel
{
    public function __construct(
        private int $orderId,
        private float $totalPrice,
        private string $shippingAddress,
        private string $status,
        private array $products
    ) {
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getShippingAddress(): string
    {
        return $this->shippingAddress;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
