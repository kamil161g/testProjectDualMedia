<?php

declare(strict_types=1);

namespace App\Model\Order;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class ProductToSummaryOrderModel
{
    private $productId;
    private $quantity;
    private $productName;

    public function __construct(int $productId, int $quantity, string $productName)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->productName = $productName;
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
