@extends('layouts.app')

@section('title', 'Ver Cliente - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detalle del Cliente</h1>
        <div>
            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-rounded me-2">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-rounded">
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
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ substr($cliente->nombre, 0, 1) }}
                        </div>
                        <h5 class="mb-0">{{ $cliente->nombre }}</h5>
                    </div>
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Documento:</td>
                            <td>{{ $cliente->documento ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Teléfono:</td>
                            <td>{{ $cliente->telefono ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email:</td>
                            <td>{{ $cliente->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Fecha Nac.:</td>
                            <td>{{ $cliente->fecha_nacimiento?->format('d/m/Y') ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dirección:</td>
                            <td>{{ $cliente->direccion ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Puntos:</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star me-1"></i>{{ number_format($cliente->puntos_fidelidad, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estado:</td>
                            <td>
                                @if($cliente->activo)
                                <span class="badge bg-success">Activo</span>
                                @else
                                <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    @if($cliente->notas)
                    <hr>
                    <h6 class="fw-bold">Notas:</h6>
                    <p class="text-muted">{{ $cliente->notas }}</p>
                    @endif
                </div>
            </div>

            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-chart-pie me-2"></i>Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $cliente->cantidad_compras }}</h4>
                            <small class="text-muted">Compras</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">${{ number_format($cliente->total_compras, 0, ',', '.') }}</h4>
                            <small class="text-muted">Total Comprado</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Historial de Compras
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th># Venta</th>
                                <th>Fecha</th>
                                <th>Productos</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cliente->ventas as $venta)
                            <tr>
                                <td>{{ $venta->numero_venta }}</td>
                                <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $venta->detalles->sum('cantidad') }}</td>
                                <td class="fw-bold">${{ number_format($venta->total, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="mb-0">Este cliente no tiene compras registradas</p>
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
