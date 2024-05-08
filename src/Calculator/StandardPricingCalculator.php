<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Enum\RateEnum;
use App\Interfaces\PricingStrategy;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class StandardPricingCalculator implements PricingStrategy
{
    public function calculateSubtotal(array $items): float
    {
        $subtotal = 0.0;
        foreach ($items as $item) {
            $product = $item['productId'];
            $subtotal += $product->getPrice() * $item['quantity'];
        }

        return $subtotal;
    }

    public function calculateVat(float $subtotal): float
    {
        return $subtotal * RateEnum::VAT_RATE;
    }

    public function calculateTotal(float $subtotal, float $vat): float
    {
        return $subtotal + $vat;
    }
}
