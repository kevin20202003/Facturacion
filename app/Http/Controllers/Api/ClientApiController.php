<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ClientRepositoryInterface;

class ClientApiController extends Controller
{
    protected ClientRepositoryInterface $clients;

    public function __construct(ClientRepositoryInterface $clients)
    {
        $this->clients = $clients;
    }

    public function index(Request $request)
    {
        $q = $request->query('q');
        $perPage = intval($request->query('per_page', 20));
        return response()->json($this->clients->paginate($perPage, $q));
    }

    public function show($id)
    {
        $client = $this->clients->find($id);
        if (! $client) return response()->json(['message' => 'Not found'], 404);
        return response()->json($client);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'document' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
        ]);

        $client = $this->clients->create($data);
        return response()->json($client, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|nullable|email',
            'document' => 'sometimes|nullable|string|max:100',
            'phone' => 'sometimes|nullable|string|max:50',
            'address' => 'sometimes|nullable|string|max:255',
        ]);

        $ok = $this->clients->update($id, $data);
        if (! $ok) return response()->json(['message' => 'Not found'], 404);
        $client = $this->clients->find($id);
        return response()->json($client);
    }

    public function destroy($id)
    {
        $ok = $this->clients->delete($id);
        return response()->json(null, $ok ? 204 : 404);
    }
}
