<?php

namespace App\Http\Controllers;

use App\Repositories\InvoiceRepositoryInterface;
use App\Repositories\ClientRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Services\InvoiceService;
use App\Services\Tax\TaxStrategyInterface;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected InvoiceRepositoryInterface $invoices;
    protected ClientRepositoryInterface $clients;
    protected ProductRepositoryInterface $products;
    protected InvoiceService $invoiceService;
    protected TaxStrategyInterface $taxStrategy;

    public function __construct(InvoiceRepositoryInterface $invoices, ClientRepositoryInterface $clients, ProductRepositoryInterface $products, InvoiceService $invoiceService, TaxStrategyInterface $taxStrategy)
    {
        $this->invoices = $invoices;
        $this->clients = $clients;
        $this->products = $products;
        $this->invoiceService = $invoiceService;
        $this->taxStrategy = $taxStrategy;
    }

    public function index()
    {
        $invoices = $this->invoices->all();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $clients = $this->clients->all();
        $products = $this->products->all();
        $taxRate = $this->taxStrategy->getRate();
        return view('invoices.create', compact('clients', 'products', 'taxRate'));
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
        return redirect()->route('invoices.create')->with('status', 'Factura creada: '.$invoice->invoice_number);
    }
}
