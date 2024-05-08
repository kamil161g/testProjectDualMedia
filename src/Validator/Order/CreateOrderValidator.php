<?php

declare(strict_types=1);

namespace App\Validator\Order;

use App\DTO\Order\OrderInputDTO;
use App\Entity\Product;
use App\Repository\ProductRepository;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class CreateOrderValidator
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function isValid(OrderInputDTO $orderInputDTO): bool
    {
        $productIds = [];

        foreach ($orderInputDTO->getItems() as $item) {
            if (in_array($item->getProductId(), $productIds, true)) {
                return false;
            }

            $productIds[] = $item->getProductId();

            $product = $this->productRepository->getById($item->getProductId());
            if (false === $product instanceof Product || $product->isAvailability() === false) {
                return false;
            }
        }

        return true;
    }
}
