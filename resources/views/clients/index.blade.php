@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Clientes</h1>
        <a class="btn btn-primary" href="{{ route('clients.create') }}">Nuevo cliente</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Documento</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->document }}</td>
                    <td>{{ $client->phone }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
