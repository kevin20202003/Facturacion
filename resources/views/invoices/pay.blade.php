@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Pagar factura {{ $invoice->invoice_number }}</h1>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $invoice->client->name ?? '' }}</p>
            <p><strong>Total:</strong> {{ number_format($invoice->total, 2) }}</p>

            <form method="POST" action="{{ route('invoices.checkout', $invoice->id) }}">
                @csrf
                <button class="btn btn-success" type="submit">Pagar con tarjeta (Stripe)</button>
                <a class="btn btn-outline-secondary" href="{{ route('invoices.show', $invoice->id) }}">Cancelar</a>
            </form>
        </div>
    </div>

@endsection
