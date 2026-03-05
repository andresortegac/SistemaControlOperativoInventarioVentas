@extends('layouts.app')

@section('title', 'Productos - Licoreras')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Productos</h1>
        <div>
            <a href="{{ route('productos.stock-bajo') }}" class="btn btn-warning btn-rounded me-2">
                <i class="fas fa-exclamation-triangle me-2"></i>Stock Bajo
            </a>
            <a href="{{ route('productos.create') }}" class="btn btn-primary btn-rounded">
                <i class="fas fa-plus me-2"></i>Nuevo Producto
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="table-card mb-4">
        <div class="card-body">
            <form action="{{ route('productos.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" name="buscar" placeholder="Buscar por nombre, código..." value="{{ request('buscar') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="categoria" class="form-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                        <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                        <option value="stock_bajo" {{ request('estado') == 'stock_bajo' ? 'selected' : '' }}>Stock Bajo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-box me-2"></i>Lista de Productos
            </h6>
            <span class="badge bg-primary">{{ $productos->total() }} productos</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                    <tr>
                        <td>
                            <code>{{ $producto->codigo }}</code>
                            @if($producto->codigo_barras)
                            <br><small class="text-muted">{{ $producto->codigo_barras }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($producto->imagen)
                                <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                @else
                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-box text-muted"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $producto->nombre }}</div>
                                    <small class="text-muted">{{ $producto->unidad_medida }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background-color: {{ $producto->categoria->color ?? '#6c757d' }}">
                                {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                            </span>
                        </td>
                        <td>${{ number_format($producto->precio_compra, 0, ',', '.') }}</td>
                        <td class="fw-bold">${{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $producto->stock_bajo ? 'danger' : ($producto->stock == 0 ? 'dark' : 'success') }}">
                                {{ $producto->stock }}
                            </span>
                        </td>
                        <td>
                            @if($producto->activo)
                            <span class="badge bg-success">Activo</span>
                            @else
                            <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('productos.show', $producto) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($producto->codigo_barras)
                                <a href="{{ route('productos.barcode', $producto) }}" class="btn btn-sm btn-outline-info" title="Código de Barras" target="_blank">
                                    <i class="fas fa-barcode"></i>
                                </a>
                                @endif
                                @if($producto->activo)
                                <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desactivar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Desactivar">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('productos.activar', $producto) }}" method="POST" class="d-inline">
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
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="mb-0">No se encontraron productos</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $productos->links() }}
        </div>
    </div>
</div>
@endsection
