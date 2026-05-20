<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    public function created(Invoice $invoice): void
    {
        foreach ($invoice->items as $item) {
            $product = $item->product;
            if ($product) {
                // decrement stock, but do not go negative
                $product->decrement('stock', $item->quantity);
            }
        }
    }
}
