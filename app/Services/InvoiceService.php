<?php

namespace App\Services;

use App\Repositories\InvoiceRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Services\Tax\TaxStrategyInterface;

class InvoiceService
{
    protected InvoiceRepositoryInterface $invoices;
    protected ProductRepositoryInterface $products;
    protected TaxStrategyInterface $taxStrategy;

    public function __construct(InvoiceRepositoryInterface $invoices, ProductRepositoryInterface $products, TaxStrategyInterface $taxStrategy)
    {
        $this->invoices = $invoices;
        $this->products = $products;
        $this->taxStrategy = $taxStrategy;
    }

    /**
     * Create an invoice and its items. Items: [ ['product_id'=>int,'quantity'=>int,'unit_price'=>float], ... ]
     */
    public function createInvoice(array $data, array $items)
    {
        $subtotal = 0;
        foreach ($items as &$i) {
            $i['total'] = round($i['quantity'] * $i['unit_price'], 2);
            $subtotal += $i['total'];
        }
        unset($i);

        $tax = $this->taxStrategy->calculate($subtotal);
        $total = round($subtotal + $tax, 2);

        $invoiceData = array_merge($data, [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);

        $invoice = $this->invoices->createWithItems($invoiceData, $items);
        return $invoice;
    }
}
