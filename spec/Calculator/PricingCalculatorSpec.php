<?php

namespace spec\App\Calculator;

use App\Calculator\PricingCalculator;
use App\Interfaces\PricingStrategy;
use PhpSpec\ObjectBehavior;

class PricingCalculatorSpec extends ObjectBehavior
{
    public function let(PricingStrategy $pricingStrategy): void
    {
        $this->beConstructedWith($pricingStrategy);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PricingCalculator::class);
    }

    public function it_calculates_order_pricing(PricingStrategy $pricingStrategy): void
    {
        $items = [['price' => 10.0, 'quantity' => 2]];

        $pricingStrategy->calculateSubtotal($items)->willReturn(650.0); // 100*2 + 150*3
        $pricingStrategy->calculateVat(650.0)->willReturn(149.5); // 23% VAT
        $pricingStrategy->calculateTotal(650.0, 149.5)->willReturn(799.5);

        $this->calculateOrder($items)->shouldReturn([
            'subtotal' => 650.0,
            'vat' => 149.5,
            'total' => 799.5
        ]);
    }
}
