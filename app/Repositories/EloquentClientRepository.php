<?php

namespace App\Repositories;

use App\Models\Client;

class EloquentClientRepository implements ClientRepositoryInterface
{
    public function all()
    {
        return Client::all();
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        $query = Client::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('document', 'like', "%{$search}%");
        }
        return $query->paginate($perPage);
    }

    public function find(int $id)
    {
        return Client::find($id);
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $client = Client::find($id);
        if (!$client) return false;
        return $client->update($data);
    }

    public function delete(int $id): bool
    {
        $client = Client::find($id);
        if (!$client) return false;
        return $client->delete();
    }
}
