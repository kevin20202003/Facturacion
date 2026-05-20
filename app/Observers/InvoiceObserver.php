<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\Notifier;

class InvoiceObserver
{
    protected function currentUserId()
    {
        return Auth::id();
    }

    public function created(Invoice $invoice): void
    {
        foreach ($invoice->items as $item) {
            $product = $item->product;
            if ($product) {
                // decrement stock, but do not go negative
                $product->decrement('stock', $item->quantity);
            }
        }

        // record audit
        try {
            $new = $invoice->toArray();
            $new['items'] = $invoice->items->map(function($it) {
                return [
                    'product_id' => $it->product_id,
                    'quantity' => $it->quantity,
                    'unit_price' => $it->unit_price ?? null,
                    'total' => $it->total ?? null,
                ];
            })->toArray();

            AuditLog::create([
                'auditable_type' => 'Invoice',
                'auditable_id' => $invoice->id,
                'user_id' => $this->currentUserId(),
                'event' => 'created',
                'old_values' => null,
                'new_values' => $new,
            ]);
        } catch (\Throwable $e) {
            Notifier::critical('Failed to record invoice created audit: ' . $e->getMessage(), ['invoice_id' => $invoice->id]);
        }
    }

    public function updated(Invoice $invoice): void
    {
        try {
            $old = $invoice->getOriginal();
            $changes = $invoice->getChanges();

            AuditLog::create([
                'auditable_type' => 'Invoice',
                'auditable_id' => $invoice->id,
                'user_id' => $this->currentUserId(),
                'event' => 'updated',
                'old_values' => $old,
                'new_values' => $changes,
            ]);
        } catch (\Throwable $e) {
            Notifier::critical('Failed to record invoice updated audit: ' . $e->getMessage(), ['invoice_id' => $invoice->id]);
        }
    }

    public function deleted(Invoice $invoice): void
    {
        try {
            $old = $invoice->getOriginal();
            AuditLog::create([
                'auditable_type' => 'Invoice',
                'auditable_id' => $invoice->id,
                'user_id' => $this->currentUserId(),
                'event' => 'deleted',
                'old_values' => $old,
                'new_values' => null,
            ]);
        } catch (\Throwable $e) {
            Notifier::critical('Failed to record invoice deleted audit: ' . $e->getMessage(), ['invoice_id' => $invoice->id]);
        }
    }
}
