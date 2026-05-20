@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Reporte de Ventas</h1>
    </div>

    <form method="GET" class="mb-3 row g-2">
        <div class="col-auto">
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Desde">
        </div>
        <div class="col-auto">
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Hasta">
        </div>
        <div class="col-auto">
            <select name="client_id" class="form-select">
                <option value="">Todos los clientes</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" @selected(request('client_id') == $client->id)>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="submit">Filtrar</button>
            <a class="btn btn-outline-secondary" href="{{ route('reports.sales', array_merge(request()->all(), ['export' => 'csv'])) }}">Exportar CSV</a>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <p><strong>Total:</strong> {{ number_format($total, 2) }}</p>

            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Subtotal</th>
                            <th>Impuesto</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->client->name ?? '' }}</td>
                                <td>{{ $invoice->date }}</td>
                                <td>{{ number_format($invoice->subtotal, 2) }}</td>
                                <td>{{ number_format($invoice->tax, 2) }}</td>
                                <td>{{ number_format($invoice->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
