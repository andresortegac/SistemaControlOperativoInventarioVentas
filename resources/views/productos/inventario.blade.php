@extends('layouts.app')

@section('title', 'Inventario - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inventario Completo</h1>
        <div>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-rounded">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card success">
                <div class="text-center">
                    <h3 class="text-success">${{ number_format($totalValor, 0, ',', '.') }}</h3>
                    <small class="text-muted">Valor Total del Inventario</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card primary">
                <div class="text-center">
                    <h3 class="text-primary">{{ $productos->count() }}</h3>
                    <small class="text-muted">Total Productos</small>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-warehouse me-2"></i>Detalle de Inventario
            </h6>
            <button onclick="window.print()" class="btn btn-sm btn-outline-primary no-print">
                <i class="fas fa-print me-2"></i>Imprimir
            </button>
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
                        <th>Valor Inventario</th>
                        <th>Precio Venta</th>
                        <th>Ganancia</th>
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
                        <td class="fw-bold">${{ number_format($producto->stock * $producto->precio_compra, 0, ',', '.') }}</td>
                        <td>${{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                        <td class="text-success">${{ number_format($producto->ganancia, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <p class="mb-0">No hay productos registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="5" class="text-end fw-bold">TOTAL:</td>
                        <td class="fw-bold">${{ number_format($totalValor, 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
