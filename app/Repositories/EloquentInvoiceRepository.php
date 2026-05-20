<?php

namespace App\Repositories;

use App\Models\Invoice;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    public function find(int $id)
    {
        return Invoice::with('items')->find($id);
    }

    public function all()
    {
        return Invoice::with('client', 'items.product')->get();
    }

    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function createWithItems(array $invoiceData, array $items): Invoice
    {
        $invoice = Invoice::create($invoiceData);
        foreach ($items as $item) {
            $invoice->items()->create($item);
        }
        return $invoice->load('items');
    }
}
