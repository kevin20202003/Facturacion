@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Productos</h1>
        <a class="btn btn-primary" href="{{ route('products.create') }}">Nuevo producto</a>
    </div>

    <form method="GET" class="mb-3">
        <div class="input-group">
            <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar productos...">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>SKU</th>
                <th>Precio</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        {{ $products->links() }}
    </div>
@endsection
