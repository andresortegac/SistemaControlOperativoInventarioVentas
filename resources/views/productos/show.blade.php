@extends('layouts.app')

@section('title', 'Ver Producto - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detalle del Producto</h1>
        <div>
            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-rounded me-2">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-rounded">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="table-card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Información General
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($producto->imagen)
                        <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-fluid rounded" style="max-height: 200px;">
                        @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" style="width: 200px; height: 200px;">
                            <i class="fas fa-box fa-5x text-muted"></i>
                        </div>
                        @endif
                    </div>
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Código:</td>
                            <td class="fw-bold">{{ $producto->codigo }}</td>
                        </tr>
                        @if($producto->codigo_barras)
                        <tr>
                            <td class="text-muted">Código de Barras:</td>
                            <td>{{ $producto->codigo_barras }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Nombre:</td>
                            <td>{{ $producto->nombre }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Categoría:</td>
                            <td>
                                <span class="badge" style="background-color: {{ $producto->categoria->color ?? '#6c757d' }}">
                                    {{ $producto->categoria->nombre ?? 'N/A' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Proveedor:</td>
                            <td>{{ $producto->proveedor->nombre ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Unidad:</td>
                            <td>{{ $producto->unidad_medida }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estado:</td>
                            <td>
                                @if($producto->activo)
                                <span class="badge bg-success">Activo</span>
                                @else
                                <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($barcode)
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-barcode me-2"></i>Código de Barras
                    </h6>
                </div>
                <div class="card-body text-center">
                    {!! $barcode !!}
                    <p class="mt-2 text-muted">{{ $producto->codigo_barras }}</p>
                    <a href="{{ route('productos.barcode', $producto) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-download me-2"></i>Descargar
                    </a>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="text-center">
                            <h4 class="text-primary">${{ number_format($producto->precio_compra, 0, ',', '.') }}</h4>
                            <small class="text-muted">Precio Compra</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="text-center">
                            <h4 class="text-success">${{ number_format($producto->precio_venta, 0, ',', '.') }}</h4>
                            <small class="text-muted">Precio Venta</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="text-center">
                            <h4 class="text-info">${{ number_format($producto->ganancia, 0, ',', '.') }}</h4>
                            <small class="text-muted">Ganancia</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="text-center">
                            <h4 class="text-{{ $producto->stock_bajo ? 'danger' : 'success' }}">{{ $producto->stock }}</h4>
                            <small class="text-muted">Stock</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-arrow-down me-2"></i>Últimas Entradas
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Cantidad</th>
                                <th>Costo Unit.</th>
                                <th>Proveedor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($producto->entradasInventario->take(5) as $entrada)
                            <tr>
                                <td>{{ $entrada->fecha_entrada->format('d/m/Y') }}</td>
                                <td><span class="badge bg-success">+{{ $entrada->cantidad }}</span></td>
                                <td>${{ $entrada->costo_unitario ? number_format($entrada->costo_unitario, 0, ',', '.') : 'N/A' }}</td>
                                <td>{{ $entrada->proveedor->nombre ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">No hay entradas registradas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-arrow-up me-2"></i>Últimas Salidas
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Cantidad</th>
                                <th>Motivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($producto->salidasInventario->take(5) as $salida)
                            <tr>
                                <td>{{ $salida->fecha_salida->format('d/m/Y') }}</td>
                                <td><span class="badge bg-danger">-{{ $salida->cantidad }}</span></td>
                                <td>{{ $salida->motivo_texto }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-3">No hay salidas registradas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
