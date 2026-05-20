<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Product;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Date filters (default last 30 days)
        try {
            $to = $request->query('to') ? Carbon::parse($request->query('to')) : Carbon::today();
        } catch (\Throwable $e) {
            $to = Carbon::today();
        }

        try {
            $from = $request->query('from') ? Carbon::parse($request->query('from')) : Carbon::today()->subDays(29);
        } catch (\Throwable $e) {
            $from = Carbon::today()->subDays(29);
        }

        if ($from->gt($to)) {
            // swap if invalid range
            [$from, $to] = [$to->copy()->subDays(29), $from->copy()];
        }

        $clientsCount = Client::count();
        $productsCount = Product::count();
        $invoicesCount = Invoice::count();
        $paidTotal = Invoice::where('status', 'paid')->sum('total');
        $pendingCount = Invoice::where('status', 'pending')->count();
        $recentInvoices = Invoice::with('client')->orderBy('created_at', 'desc')->take(6)->get();

        // Build labels for chart (inclusive)
        $labels = [];
        $cursor = $from->copy();
        while ($cursor->lte($to)) {
            $labels[] = $cursor->format('Y-m-d');
            $cursor->addDay();
        }

        // Sales aggregation per day
        $rows = DB::table('invoices')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date')
            ->toArray();

        $salesData = array_map(function ($d) use ($rows) {
            return isset($rows[$d]) ? (float) $rows[$d] : 0.0;
        }, $labels);

        // Top clients by sales in range
        $topClientsRaw = DB::table('invoices')
            ->select('client_id', DB::raw('SUM(total) as total'))
            ->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()])
            ->groupBy('client_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topClients = $topClientsRaw->map(function ($r) {
            $client = Client::find($r->client_id);
            return [
                'label' => $client->name ?? 'Cliente '.$r->client_id,
                'total' => (float) $r->total,
            ];
        });

        // Top products by sales
        $topProductsRaw = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('products', 'invoice_items.product_id', '=', 'products.id')
            ->whereBetween('invoices.created_at', [$from->startOfDay(), $to->endOfDay()])
            ->select('products.id', 'products.name', DB::raw('SUM(invoice_items.quantity * invoice_items.unit_price) as total_sales'), DB::raw('SUM(invoice_items.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        $topProducts = $topProductsRaw->map(function ($r) {
            return [
                'label' => $r->name,
                'sales' => (float) $r->total_sales,
                'qty' => (int) $r->total_qty,
            ];
        });

        return view('dashboard.index', compact(
            'clientsCount',
            'productsCount',
            'invoicesCount',
            'paidTotal',
            'pendingCount',
            'recentInvoices',
            'labels',
            'salesData',
            'topClients',
            'topProducts',
            'from',
            'to'
        ));
    }
}
