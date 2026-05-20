<?php

namespace App\Http\Controllers;

use App\Repositories\ClientRepositoryInterface;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected ClientRepositoryInterface $clients;

    public function __construct(ClientRepositoryInterface $clients)
    {
        $this->clients = $clients;
    }

    public function index()
    {
        $q = request('q');
        $clients = $this->clients->paginate(15, $q);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'document' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $this->clients->create($data);
        return redirect()->route('clients.index');
    }
}
