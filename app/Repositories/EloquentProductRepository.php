<?php

namespace App\Repositories;

use App\Models\Product;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return Product::all();
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        $query = Product::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        }
        return $query->paginate($perPage);
    }

    public function find(int $id)
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $product = Product::find($id);
        if (!$product) return false;
        return $product->update($data);
    }

    public function delete(int $id): bool
    {
        $product = Product::find($id);
        if (!$product) return false;
        return $product->delete();
    }
}
