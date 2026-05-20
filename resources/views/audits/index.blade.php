@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Auditoría</h1>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Entidad</th>
                        <th>Evento</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($audits as $audit)
                        <tr>
                            <td>{{ $audit->created_at }}</td>
                            <td>{{ $audit->user->name ?? 'Sistema' }}</td>
                            <td>{{ $audit->auditable_type }} #{{ $audit->auditable_id }}</td>
                            <td>{{ $audit->event }}</td>
                            <td style="max-width: 420px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <pre style="margin:0;">Old: {{ json_encode($audit->old_values) }}
New: {{ json_encode($audit->new_values) }}</pre>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $audits->links() }}
            </div>
        </div>
    </div>

@endsection
