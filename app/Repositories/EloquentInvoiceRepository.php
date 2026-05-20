<?php

namespace App\Repositories;

use App\Models\Invoice;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    public function find(int $id)
    {
        return Invoice::with('items')->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        $query = Invoice::with('client', 'items.product');
        if ($search) {
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }
        return $query->paginate($perPage);
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
