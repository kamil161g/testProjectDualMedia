<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Interfaces\PricingStrategy;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class PricingCalculator
{
    private PricingStrategy $pricingStrategy;

    public function __construct(PricingStrategy $pricingStrategy)
    {
        $this->pricingStrategy = $pricingStrategy;
    }

    public function calculateOrder(array $items): array
    {
        $subtotal = $this->pricingStrategy->calculateSubtotal($items);
        $vat = $this->pricingStrategy->calculateVat($subtotal);
        $total = $this->pricingStrategy->calculateTotal($subtotal, $vat);

        return [
            'subtotal' => $subtotal,
            'vat' => $vat,
            'total' => $total
        ];
    }
}
