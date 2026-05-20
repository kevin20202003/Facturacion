<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Invoice;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret') ?: env('STRIPE_WEBHOOK_SECRET');

        if (empty($secret)) {
            Log::warning('Stripe webhook received but no webhook secret configured.');
            return response('Webhook secret not configured', 500);
        }

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::warning('Invalid Stripe payload: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Invalid Stripe signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $invoiceId = $session->metadata->invoice_id ?? null;
                $paymentStatus = $session->payment_status ?? null;
                if ($invoiceId && $paymentStatus === 'paid') {
                    $invoice = Invoice::find($invoiceId);
                    if ($invoice) {
                        $invoice->update(['status' => 'paid']);
                        Log::info('Invoice marked as paid via webhook: ' . $invoiceId);
                    }
                }
                break;
            default:
                Log::info('Unhandled Stripe event type: ' . $event->type);
        }

        return response('OK', 200);
    }
}
