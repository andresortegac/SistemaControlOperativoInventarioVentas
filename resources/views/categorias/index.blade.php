@extends('layouts.app')

@section('title', 'Categorías - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Categorías</h1>
        <a href="{{ route('categorias.create') }}" class="btn btn-primary btn-rounded">
            <i class="fas fa-plus me-2"></i>Nueva Categoría
        </a>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-tags me-2"></i>Lista de Categorías
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Color</th>
                        <th>Productos</th>
                        <th>Estado</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                    <tr>
                        <td>
                            <span class="badge" style="background-color: {{ $categoria->color }}">
                                <i class="fas fa-tag me-1"></i>{{ $categoria->nombre }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div style="width: 30px; height: 30px; background-color: {{ $categoria->color }}; border-radius: 5px; margin-right: 10px;"></div>
                                <code>{{ $categoria->color }}</code>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $categoria->productos_count }} productos</span>
                        </td>
                        <td>
                            @if($categoria->activo)
                            <span class="badge bg-success">Activa</span>
                            @else
                            <span class="badge bg-secondary">Inactiva</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta categoría?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="mb-0">No hay categorías registradas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $categorias->links() }}
        </div>
    </div>
</div>
@endsection
