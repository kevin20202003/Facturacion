<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\AuditLog;
use App\Services\Notifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    protected function currentUserId()
    {
        return Auth::id();
    }

    public function created(Product $product): void
    {
        try {
            AuditLog::create([
                'auditable_type' => 'Product',
                'auditable_id' => $product->id,
                'user_id' => $this->currentUserId(),
                'event' => 'created',
                'old_values' => null,
                'new_values' => $product->toArray(),
            ]);
        } catch (\Throwable $e) {
            Notifier::critical('Failed to record product created audit: ' . $e->getMessage(), ['product_id' => $product->id]);
        }
    }

    public function updated(Product $product): void
    {
        try {
            AuditLog::create([
                'auditable_type' => 'Product',
                'auditable_id' => $product->id,
                'user_id' => $this->currentUserId(),
                'event' => 'updated',
                'old_values' => $product->getOriginal(),
                'new_values' => $product->getChanges(),
            ]);
        } catch (\Throwable $e) {
            Notifier::critical('Failed to record product updated audit: ' . $e->getMessage(), ['product_id' => $product->id]);
        }
    }

    public function deleted(Product $product): void
    {
        try {
            AuditLog::create([
                'auditable_type' => 'Product',
                'auditable_id' => $product->id,
                'user_id' => $this->currentUserId(),
                'event' => 'deleted',
                'old_values' => $product->getOriginal(),
                'new_values' => null,
            ]);
        } catch (\Throwable $e) {
            Notifier::critical('Failed to record product deleted audit: ' . $e->getMessage(), ['product_id' => $product->id]);
        }
    }
}
