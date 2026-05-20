<?php

namespace App\Http\Controllers;

use App\Repositories\InvoiceRepositoryInterface;
use App\Repositories\ClientRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Services\InvoiceService;
use App\Services\Tax\TaxStrategyInterface;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceCreated;
use Illuminate\Support\Facades\Log;

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
        $q = request('q');
        $invoices = $this->invoices->paginate(15, $q);
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

        // Cargar relaciones necesarias
        $invoice->load('client', 'items.product');

        // Intentar generar PDF y enviar correo al cliente (no debe romper la creación si falla)
        try {
            $pdfData = PDF::loadView('invoices.pdf', compact('invoice'))->output();
            if (! empty($invoice->client->email)) {
                Mail::to($invoice->client->email)->send(new InvoiceCreated($invoice, $pdfData));
            }
        } catch (\Throwable $e) {
            Log::error('Fallo al enviar correo de factura: ' . $e->getMessage());
        }

        return redirect()->route('invoices.create')->with('status', 'Factura creada: '.$invoice->invoice_number);
    }

    public function show($id)
    {
        $invoice = $this->invoices->find($id);
        if (! $invoice) {
            abort(404);
        }
        return view('invoices.show', compact('invoice'));
    }

    public function pdf($id)
    {
        $invoice = $this->invoices->find($id);
        if (! $invoice) {
            abort(404);
        }

        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('factura_' . $invoice->invoice_number . '.pdf');
    }
}
