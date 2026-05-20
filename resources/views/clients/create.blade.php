@extends('layouts.app')

@section('content')
    <h1>Nuevo Cliente</h1>

    <form method="POST" action="{{ route('clients.store') }}" class="row g-3">
        @csrf
        <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input name="name" class="form-control" required />
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input name="email" class="form-control" />
        </div>
        <div class="col-md-4">
            <label class="form-label">Documento</label>
            <input name="document" class="form-control" />
        </div>
        <div class="col-md-4">
            <label class="form-label">Teléfono</label>
            <input name="phone" class="form-control" />
        </div>
        <div class="col-12">
            <label class="form-label">Dirección</label>
            <textarea name="address" class="form-control"></textarea>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
