@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1>Iniciar sesión</h1>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" class="form-control" required />
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input name="password" type="password" class="form-control" required />
                </div>

                <div class="mb-3 form-check">
                    <input name="remember" class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>

                <div class="mb-3">
                    <button class="btn btn-primary" type="submit">Entrar</button>
                    <a class="btn btn-link" href="{{ route('register') }}">Crear cuenta</a>
                </div>
            </form>
        </div>
    </div>
@endsection
