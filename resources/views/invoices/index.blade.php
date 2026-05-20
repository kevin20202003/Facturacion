@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Facturas</h1>
        <a class="btn btn-primary" href="{{ route('invoices.create') }}">Nueva factura</a>
    </div>

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
    @endif
@endsection
