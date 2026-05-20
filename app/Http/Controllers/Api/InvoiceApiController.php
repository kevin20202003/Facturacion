<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\InvoiceRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Services\InvoiceService;

class InvoiceApiController extends Controller
{
    protected InvoiceRepositoryInterface $invoices;
    protected ProductRepositoryInterface $products;
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceRepositoryInterface $invoices, ProductRepositoryInterface $products, InvoiceService $invoiceService)
    {
        $this->invoices = $invoices;
        $this->products = $products;
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $q = $request->query('q');
        $perPage = intval($request->query('per_page', 20));
        return response()->json($this->invoices->paginate($perPage, $q));
    }

    public function show($id)
    {
        $invoice = $this->invoices->find($id);
        if (! $invoice) return response()->json(['message' => 'Not found'], 404);
        $invoice->load('client', 'items.product');
        return response()->json($invoice);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'invoice_number' => 'required|string',
            'date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $itemsRaw = $data['items'] ?? [];
        $items = [];
        foreach ($itemsRaw as $raw) {
            if (empty($raw['product_id']) || empty($raw['quantity'])) continue;
            $product = $this->products->find(intval($raw['product_id']));
            if (!$product) continue;
            $items[] = [
                'product_id' => $product->id,
                'quantity' => intval($raw['quantity']),
                'unit_price' => $product->price,
            ];
        }

        $invoice = $this->invoiceService->createInvoice(array_merge($data, ['client_id' => $data['client_id']]), $items);
        $invoice->load('client', 'items.product');
        return response()->json($invoice, 201);
    }
}
