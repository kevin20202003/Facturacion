<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Factura {{ $invoice->invoice_number }}</h2>
    <p><strong>Cliente:</strong> {{ $invoice->client->name ?? '' }}</p>
    <p><strong>Fecha:</strong> {{ $invoice->date ?? '' }}</p>

    <table>
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
                <td>{{ $item->product->name ?? '' }}</td>
                <td>{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="text-right">Subtotal: {{ number_format($invoice->subtotal, 2) }}</p>
    <p class="text-right">Impuesto: {{ number_format($invoice->tax, 2) }}</p>
    <p class="text-right"><strong>Total: {{ number_format($invoice->total, 2) }}</strong></p>
</body>
</html>
