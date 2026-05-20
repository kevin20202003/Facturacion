<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\AuditLog;
use App\Services\Notifier;
use Illuminate\Support\Facades\Auth;

class ClientObserver
{
    protected function currentUserId()
    {
        return Auth::id();
    }

    public function created(Client $client): void
    {
        try {
            AuditLog::create([
                'auditable_type' => 'Client',
                'auditable_id' => $client->id,
                'user_id' => $this->currentUserId(),
                'event' => 'created',
                'old_values' => null,
                'new_values' => $client->toArray(),
            ]);
        } catch (\Throwable $e) {
            Notifier::critical('Failed to record client created audit: ' . $e->getMessage(), ['client_id' => $client->id]);
        }
    }

    public function updated(Client $client): void
    {
        try {
            AuditLog::create([
                'auditable_type' => 'Client',
                'auditable_id' => $client->id,
                'user_id' => $this->currentUserId(),
                'event' => 'updated',
                'old_values' => $client->getOriginal(),
                'new_values' => $client->getChanges(),
            ]);
        } catch (\Throwable $e) {
            Notifier::critical('Failed to record client updated audit: ' . $e->getMessage(), ['client_id' => $client->id]);
        }
    }

    public function deleted(Client $client): void
    {
        try {
            AuditLog::create([
                'auditable_type' => 'Client',
                'auditable_id' => $client->id,
                'user_id' => $this->currentUserId(),
                'event' => 'deleted',
                'old_values' => $client->getOriginal(),
                'new_values' => null,
            ]);
        } catch (\Throwable $e) {
            Notifier::critical('Failed to record client deleted audit: ' . $e->getMessage(), ['client_id' => $client->id]);
        }
    }
}
