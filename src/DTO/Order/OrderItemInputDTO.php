<?php

declare(strict_types=1);

namespace App\DTO\Order;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
readonly class OrderItemInputDTO
{
    private int $productId;
    private int $quantity;

    public function __construct(int $productId, int $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['product_id'],
            $data['quantity']
        );
    }
}
