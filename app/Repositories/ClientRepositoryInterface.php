<?php

namespace App\Repositories;

use App\Models\Client;

interface ClientRepositoryInterface
{
    public function all();
    public function paginate(int $perPage = 15, ?string $search = null);
    public function find(int $id);
    public function create(array $data): Client;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
