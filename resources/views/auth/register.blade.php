@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1>Registro</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input name="name" value="{{ old('name') }}" class="form-control" required />
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" class="form-control" required />
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input name="password" type="password" class="form-control" required />
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmar contraseña</label>
                    <input name="password_confirmation" type="password" class="form-control" required />
                </div>

                <div class="mb-3">
                    <button class="btn btn-primary" type="submit">Crear cuenta</button>
                    <a class="btn btn-link" href="{{ route('login') }}">Tengo cuenta</a>
                </div>
            </form>
        </div>
    </div>
@endsection
