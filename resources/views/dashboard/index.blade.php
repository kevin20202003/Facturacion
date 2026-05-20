@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2>Dashboard</h2>
            <p class="text-muted mb-0">Resumen rápido del sistema</p>
        </div>
        <form method="GET" class="row gx-2 gy-2 align-items-center">
            <div class="col-auto">
                <input type="date" name="from" class="form-control" value="{{ optional($from)->format('Y-m-d') }}">
            </div>
            <div class="col-auto">
                <input type="date" name="to" class="form-control" value="{{ optional($to)->format('Y-m-d') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-secondary">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-2 col-6">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title">Clientes</h6>
                <p class="display-6 mb-0">{{ $clientsCount }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title">Productos</h6>
                <p class="display-6 mb-0">{{ $productsCount }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title">Facturas</h6>
                <p class="display-6 mb-0">{{ $invoicesCount }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title">Facturas pendientes</h6>
                <p class="display-6 mb-0">{{ $pendingCount }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-12">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title">Ingresos confirmados</h6>
                <p class="display-6 mb-0">{{ number_format($paidTotal, 2) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-8 mb-3">
        <div class="card h-100">
            <div class="card-header">Ventas ({{ optional($from)->format('Y-m-d') }} — {{ optional($to)->format('Y-m-d') }})</div>
            <div class="card-body">
                <canvas id="salesChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card h-100">
            <div class="card-header">Top clientes</div>
            <div class="card-body">
                <canvas id="clientsChart" height="160"></canvas>
                <hr>
                <ul class="list-unstyled mb-0">
                    @foreach($topClients as $c)
                        <li class="d-flex justify-content-between py-1">
                            <span>{{ $c['label'] }}</span>
                            <strong>{{ number_format($c['total'], 2) }}</strong>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-3">
        <div class="card">
            <div class="card-header">Facturas recientes</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInvoices as $inv)
                                <tr>
                                    <td>{{ $inv->invoice_number }}</td>
                                    <td>{{ $inv->client->name ?? '-' }}</td>
                                    <td>{{ optional($inv->created_at)->format('Y-m-d') }}</td>
                                    <td>{{ number_format($inv->total, 2) }}</td>
                                    <td>{{ ucfirst($inv->status) }}</td>
                                    <td><a href="{{ route('invoices.show', $inv->id) }}" class="btn btn-sm btn-link">Ver</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">No hay facturas recientes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-header">Top productos</div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($topProducts as $p)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $p['label'] }}</div>
                                <small class="text-muted">Cantidad: {{ $p['qty'] }}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ number_format($p['sales'], 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const salesLabels = @json($labels);
        const salesData = @json($salesData);

        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Ventas',
                    data: salesData,
                    borderColor: 'rgba(54,162,235,1)',
                    backgroundColor: 'rgba(54,162,235,0.15)',
                    fill: true,
                    tension: 0.2
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        const clientLabels = @json($topClients->pluck('label')->toArray());
        const clientTotals = @json($topClients->pluck('total')->toArray());
        const clientsCtx = document.getElementById('clientsChart').getContext('2d');
        new Chart(clientsCtx, {
            type: 'bar',
            data: {
                labels: clientLabels,
                datasets: [{
                    label: 'Ventas por cliente',
                    data: clientTotals,
                    backgroundColor: ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b']
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    </script>
@endsection
