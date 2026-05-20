<?php

namespace App\Services\Tax;

interface TaxStrategyInterface
{
    public function calculate(float $subtotal): float;

    /**
     * Return the tax rate as a decimal (e.g. 0.21 for 21%).
     */
    public function getRate(): float;
}
