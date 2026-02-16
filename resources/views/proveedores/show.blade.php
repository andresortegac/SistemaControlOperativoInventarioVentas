@extends('layouts.app')

@section('title', 'Ver Proveedor - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detalle del Proveedor</h1>
        <div>
            <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-warning btn-rounded me-2">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary btn-rounded">
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
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Nombre:</td>
                            <td class="fw-bold">{{ $proveedor->nombre }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Contacto:</td>
                            <td>{{ $proveedor->nombre_contacto ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Teléfono:</td>
                            <td>{{ $proveedor->telefono ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email:</td>
                            <td>{{ $proveedor->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">NIT:</td>
                            <td>{{ $proveedor->nit ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dirección:</td>
                            <td>{{ $proveedor->direccion ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estado:</td>
                            <td>
                                @if($proveedor->activo)
                                <span class="badge bg-success">Activo</span>
                                @else
                                <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    @if($proveedor->notas)
                    <hr>
                    <h6 class="fw-bold">Notas:</h6>
                    <p class="text-muted">{{ $proveedor->notas }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-box me-2"></i>Productos del Proveedor
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Precio Compra</th>
                                <th>Stock</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proveedor->productos as $producto)
                            <tr>
                                <td>{{ $producto->codigo }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>${{ number_format($producto->precio_compra, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $producto->stock_bajo ? 'danger' : 'success' }}">
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
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="mb-0">No hay productos de este proveedor</p>
                                </td>
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
