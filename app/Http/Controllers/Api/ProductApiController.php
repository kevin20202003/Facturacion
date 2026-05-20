<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProductRepositoryInterface;

class ProductApiController extends Controller
{
    protected ProductRepositoryInterface $products;

    public function __construct(ProductRepositoryInterface $products)
    {
        $this->products = $products;
    }

    public function index(Request $request)
    {
        $q = $request->query('q');
        $perPage = intval($request->query('per_page', 20));
        return response()->json($this->products->paginate($perPage, $q));
    }

    public function show($id)
    {
        $product = $this->products->find($id);
        if (! $product) return response()->json(['message' => 'Not found'], 404);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:120',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        $product = $this->products->create($data);
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'sku' => 'sometimes|nullable|string|max:120',
            'description' => 'sometimes|nullable|string|max:1000',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|nullable|integer|min:0',
        ]);

        $ok = $this->products->update($id, $data);
        if (! $ok) return response()->json(['message' => 'Not found'], 404);
        $product = $this->products->find($id);
        return response()->json($product);
    }

    public function destroy($id)
    {
        $ok = $this->products->delete($id);
        return response()->json(null, $ok ? 204 : 404);
    }
}
