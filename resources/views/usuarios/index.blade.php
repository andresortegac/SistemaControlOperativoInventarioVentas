@extends('layouts.app')

@section('title', 'Usuarios - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Usuarios</h1>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-rounded">
            <i class="fas fa-plus me-2"></i>Nuevo Usuario
        </a>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users-cog me-2"></i>Lista de Usuarios
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ $usuario->role === 'admin' ? 'danger' : ($usuario->role === 'cajero' ? 'success' : 'info') }} text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                    {{ substr($usuario->name, 0, 1) }}
                                </div>
                                <div class="fw-bold">{{ $usuario->name }}</div>
                            </div>
                        </td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            <span class="badge bg-{{ $usuario->role === 'admin' ? 'danger' : ($usuario->role === 'cajero' ? 'success' : 'info') }}">
                                {{ ucfirst($usuario->role) }}
                            </span>
                        </td>
                        <td>{{ $usuario->telefono ?? 'N/A' }}</td>
                        <td>
                            @if($usuario->activo)
                            <span class="badge bg-success">Activo</span>
                            @else
                            <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($usuario->id !== auth()->id())
                                    @if($usuario->activo)
                                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desactivar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Desactivar">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('usuarios.activar', $usuario) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Activar">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="mb-0">No hay usuarios registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $usuarios->links() }}
        </div>
    </div>
</div>
@endsection
