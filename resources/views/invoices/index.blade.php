@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Facturas</h1>
        <a class="btn btn-primary" href="{{ route('invoices.create') }}">Nueva factura</a>
    </div>

    <form method="GET" class="mb-3">
        <div class="input-group">
            <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar facturas (número o cliente)...">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
        </div>
    </form>

    @if($invoices->isEmpty())
        <div class="alert alert-info">No hay facturas aún.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->client->name ?? '' }}</td>
                        <td>{{ $invoice->date }}</td>
                        <td class="text-end">{{ number_format($invoice->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $invoices->links() }}
        </div>
    @endif
@endsection
