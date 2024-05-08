<?php

declare(strict_types=1);

namespace App\Interfaces;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
interface PricingStrategy
{
    public function calculateSubtotal(array $items): float;
    public function calculateVat(float $subtotal): float;
    public function calculateTotal(float $subtotal, float $vat): float;
}