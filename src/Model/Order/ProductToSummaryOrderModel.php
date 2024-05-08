<?php

declare(strict_types=1);

namespace App\Model\Order;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
readonly class ProductToSummaryOrderModel
{
    public function __construct(private int $productId, private int $quantity, private string $productName)
    {
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }
}
