@extends('layouts.app')

@section('title', 'Reporte de Inventario - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reporte de Inventario</h1>
        <div>
            <a href="{{ route('reportes.inventario.pdf') }}" class="btn btn-danger btn-rounded me-2">
                <i class="fas fa-file-pdf me-2"></i>PDF
            </a>
            <a href="{{ route('reportes.index') }}" class="btn btn-secondary btn-rounded">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card primary">
                <div class="text-center">
                    <h3 class="text-primary">{{ $totalProductos }}</h3>
                    <small class="text-muted">Total Productos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="text-center">
                    <h3 class="text-success">${{ number_format($valorInventario, 0, ',', '.') }}</h3>
                    <small class="text-muted">Valor Inventario</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="text-center">
                    <h3 class="text-warning">${{ number_format($valorVenta, 0, ',', '.') }}</h3>
                    <small class="text-muted">Valor Venta</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card danger">
                <div class="text-center">
                    <h3 class="text-danger">{{ $productosStockBajo }}</h3>
                    <small class="text-muted">Stock Bajo</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Productos por Categoría
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($productosPorCategoria as $categoria => $datos)
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h6 class="fw-bold">{{ $categoria }}</h6>
                                <p class="mb-1">Productos: {{ $datos['cantidad'] }}</p>
                                <p class="mb-1">Stock: {{ $datos['stock'] }}</p>
                                <p class="mb-0 text-success">Valor: ${{ number_format($datos['valor'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-box me-2"></i>Detalle de Productos
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Valor Inventario</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                    <tr>
                        <td>{{ $producto->codigo }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->categoria->nombre ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $producto->stock_bajo ? 'danger' : 'success' }}">
                                {{ $producto->stock }}
                            </span>
                        </td>
                        <td>${{ number_format($producto->precio_compra, 0, ',', '.') }}</td>
                        <td>${{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                        <td>${{ number_format($producto->stock * $producto->precio_compra, 0, ',', '.') }}</td>
                        <td>
                            @if($producto->activo)
                            <span class="badge bg-success">Activo</span>
                            @else
                            <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <p class="mb-0">No hay productos registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
