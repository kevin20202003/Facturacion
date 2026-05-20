@extends('layouts.app')

@section('content')
    <h1>Nuevo Producto</h1>
    <form method="POST" action="{{ route('products.store') }}" class="row g-3">
        @csrf
        <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input name="name" class="form-control" required />
        </div>
        <div class="col-md-6">
            <label class="form-label">SKU</label>
            <input name="sku" class="form-control" />
        </div>
        <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="col-md-4">
            <label class="form-label">Precio</label>
            <input name="price" class="form-control" required />
        </div>
        <div class="col-md-4">
            <label class="form-label">Stock</label>
            <input name="stock" class="form-control" required />
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
