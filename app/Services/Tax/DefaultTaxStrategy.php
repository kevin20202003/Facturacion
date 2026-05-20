<?php

namespace App\Services\Tax;

class DefaultTaxStrategy implements TaxStrategyInterface
{
    protected float $rate = 0.21; // 21% default

    public function __construct(?float $rate = null)
    {
        if ($rate !== null) $this->rate = $rate;
    }

    public function calculate(float $subtotal): float
    {
        return round($subtotal * $this->rate, 2);
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
