<?php

namespace App\Repositories;

use App\Models\Invoice;

interface InvoiceRepositoryInterface
{
    public function find(int $id);
    public function create(array $data): Invoice;
    public function createWithItems(array $invoiceData, array $items): Invoice;
}
