@extends('layouts.app')

@section('title', 'Proveedores - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Proveedores</h1>
        <a href="{{ route('proveedores.create') }}" class="btn btn-primary btn-rounded">
            <i class="fas fa-plus me-2"></i>Nuevo Proveedor
        </a>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-truck me-2"></i>Lista de Proveedores
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Productos</th>
                        <th>Estado</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $proveedor)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $proveedor->nombre }}</div>
                            @if($proveedor->nit)
                            <small class="text-muted">NIT: {{ $proveedor->nit }}</small>
                            @endif
                        </td>
                        <td>{{ $proveedor->nombre_contacto ?? 'N/A' }}</td>
                        <td>{{ $proveedor->telefono ?? 'N/A' }}</td>
                        <td>{{ $proveedor->email ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $proveedor->productos_count }} productos</span>
                        </td>
                        <td>
                            @if($proveedor->activo)
                            <span class="badge bg-success">Activo</span>
                            @else
                            <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('proveedores.show', $proveedor) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($proveedor->activo)
                                <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desactivar este proveedor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Desactivar">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('proveedores.activar', $proveedor) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Activar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="mb-0">No hay proveedores registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $proveedores->links() }}
        </div>
    </div>
</div>
@endsection
