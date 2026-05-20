<div style="font-family: Arial, sans-serif; font-size: 14px;">
    <p>Hola {{ $invoice->client->name ?? '' }},</p>

    <p>Adjunto la factura <strong>{{ $invoice->invoice_number }}</strong> por un total de <strong>{{ number_format($invoice->total, 2) }}</strong>.</p>

    <p>Gracias por su confianza.</p>

    <p>Saludos,<br>{{ config('app.name') }}</p>
</div>
