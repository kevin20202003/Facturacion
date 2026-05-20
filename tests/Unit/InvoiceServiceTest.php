<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Client;
use App\Models\Product;

class InvoiceServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_invoice_computes_totals_and_persists_items()
    {
        $client = Client::create([
            'name' => 'Cliente Test',
            'email' => 'cliente@example.com',
            'document' => '123456',
            'phone' => '',
            'address' => '',
        ]);

        $product = Product::create([
            'name' => 'Producto Test',
            'sku' => 'P-TEST',
            'description' => '',
            'price' => 10.00,
            'stock' => 100,
        ]);

        $service = app(\App\Services\InvoiceService::class);

        $data = ['invoice_number' => 'INV-100', 'date' => now()->toDateString(), 'client_id' => $client->id];
        $items = [ ['product_id' => $product->id, 'quantity' => 2, 'unit_price' => 10.00] ];

        $invoice = $service->createInvoice($data, $items);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'invoice_number' => 'INV-100',
        ]);

        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 10.00,
            'total' => 20.00,
        ]);

        $this->assertEquals(20.00, (float) $invoice->subtotal);
        $this->assertEquals(4.20, (float) $invoice->tax);
        $this->assertEquals(24.20, (float) $invoice->total);
    }
}
