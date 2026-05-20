<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Client;

class ReportsController extends Controller
{
    public function sales(Request $request)
    {
        $clients = Client::orderBy('name')->get();

        $query = Invoice::with('client', 'items.product');
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->input('date_to'));
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->input('client_id'));
        }

        $invoices = $query->get();
        $total = $invoices->sum('total');

        // CSV export
        if ($request->input('export') === 'csv') {
            $filename = 'report_sales_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];
            $callback = function () use ($invoices) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['invoice_number','client','date','subtotal','tax','total']);
                foreach ($invoices as $inv) {
                    fputcsv($out, [$inv->invoice_number, $inv->client->name ?? '', $inv->date, $inv->subtotal, $inv->tax, $inv->total]);
                }
                fclose($out);
            };
            return response()->stream($callback, 200, $headers);
        }

        return view('reports.sales', compact('invoices', 'total', 'clients'));
    }
}
