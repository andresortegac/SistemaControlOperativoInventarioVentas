@extends('layouts.app')

@section('title', 'Stock Bajo - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>Productos con Stock Bajo
        </h1>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-rounded">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="alert alert-warning">
        <i class="fas fa-info-circle me-2"></i>
        Los siguientes productos tienen stock igual o inferior al mínimo configurado. Considere reabastecerlos pronto.
    </div>

    <div class="table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-box me-2"></i>Lista de Productos con Stock Bajo
            </h6>
            <span class="badge bg-danger">{{ $productos->count() }} productos</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Precio Venta</th>
                        <th>Proveedor</th>
                        <th width="100">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                    <tr class="table-{{ $producto->stock == 0 ? 'danger' : 'warning' }}">
                        <td>{{ $producto->codigo }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($producto->imagen)
                                <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                @else
                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-box text-muted"></i>
                                </div>
                                @endif
                                <div class="fw-bold">{{ $producto->nombre }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background-color: {{ $producto->categoria->color ?? '#6c757d' }}">
                                {{ $producto->categoria->nombre ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $producto->stock == 0 ? 'danger' : 'warning' }} fs-6">
                                {{ $producto->stock }}
                            </span>
                        </td>
                        <td>{{ $producto->stock_minimo }}</td>
                        <td>${{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                        <td>{{ $producto->proveedor->nombre ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('inventario.entradas.create', ['producto_id' => $producto->id]) }}" class="btn btn-sm btn-success" title="Agregar Stock">
                                <i class="fas fa-plus"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="mb-0">¡Excelente! No hay productos con stock bajo</p>
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
