@extends('layouts.app')

@section('content')
    <h1>Factura {{ $invoice->invoice_number }}</h1>

    <p><strong>Cliente:</strong> {{ $invoice->client->name ?? 'N/A' }}</p>
    <p><strong>Fecha:</strong> {{ $invoice->date ?? '' }}</p>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? '—' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end">Subtotal</td>
                    <td>{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end">Impuesto</td>
                    <td>{{ number_format($invoice->tax, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    <td>{{ number_format($invoice->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-secondary">Descargar PDF</a>
    <a href="{{ route('invoices.index') }}" class="btn btn-link">Volver</a>
@endsection
