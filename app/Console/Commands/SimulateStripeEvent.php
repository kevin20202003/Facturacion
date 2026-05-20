<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;

class SimulateStripeEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:simulate {event=checkout.session.completed} {--invoice=} {--paid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate a Stripe webhook event locally (useful when Stripe CLI is not available)';

    public function handle()
    {
        $event = $this->argument('event');
        $invoiceId = $this->option('invoice');
        $paid = $this->option('paid');

        switch ($event) {
            case 'checkout.session.completed':
                if (! $invoiceId) {
                    $this->error('Provide --invoice=<id> to simulate a checkout.session.completed event.');
                    return 1;
                }

                $invoice = Invoice::find($invoiceId);
                if (! $invoice) {
                    $this->error('Invoice not found: ' . $invoiceId);
                    return 1;
                }

                $status = $paid ? 'paid' : 'pending';
                $invoice->update(['status' => $status]);

                Log::info('Simulated Stripe event: ' . $event . ' for invoice ' . $invoiceId . ' set status ' . $status);
                $this->info('Invoice ' . $invoiceId . ' updated to status: ' . $status);
                return 0;

            default:
                $this->error('Event not supported by simulator: ' . $event);
                return 1;
        }
    }
}
