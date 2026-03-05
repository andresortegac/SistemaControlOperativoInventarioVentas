@extends('layouts.app')

@section('title', 'Reporte de Ventas - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reporte de Ventas</h1>
        <div>
            <a href="{{ route('reportes.ventas.pdf', ['desde' => $desde, 'hasta' => $hasta]) }}" class="btn btn-danger btn-rounded me-2">
                <i class="fas fa-file-pdf me-2"></i>PDF
            </a>
            <a href="{{ route('reportes.index') }}" class="btn btn-secondary btn-rounded">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="table-card mb-4">
        <div class="card-body">
            <form action="{{ route('reportes.ventas') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Desde</label>
                    <input type="date" class="form-control" name="desde" value="{{ $desde }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hasta</label>
                    <input type="date" class="form-control" name="hasta" value="{{ $hasta }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Generar Reporte</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="text-center">
                    <h3 class="text-success">${{ number_format($totalVentas, 0, ',', '.') }}</h3>
                    <small class="text-muted">Total Ventas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card primary">
                <div class="text-center">
                    <h3 class="text-primary">${{ number_format($subtotal, 0, ',', '.') }}</h3>
                    <small class="text-muted">Subtotal</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="text-center">
                    <h3 class="text-warning">${{ number_format($descuentos, 0, ',', '.') }}</h3>
                    <small class="text-muted">Descuentos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <div class="text-center">
                    <h3 class="text-info">{{ $cantidadVentas }}</h3>
                    <small class="text-muted">Cantidad Ventas</small>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-shopping-cart me-2"></i>Detalle de Ventas
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th># Venta</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Productos</th>
                        <th>Subtotal</th>
                        <th>Descuento</th>
                        <th>Impuesto</th>
                        <th>Total</th>
                        <th>Método</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                    <tr>
                        <td>{{ $venta->numero_venta }}</td>
                        <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $venta->cliente ? $venta->cliente->nombre : 'Cliente General' }}</td>
                        <td>{{ $venta->detalles->sum('cantidad') }}</td>
                        <td>${{ number_format($venta->subtotal, 0, ',', '.') }}</td>
                        <td>${{ number_format($venta->descuento, 0, ',', '.') }}</td>
                        <td>${{ number_format($venta->impuesto, 0, ',', '.') }}</td>
                        <td class="fw-bold">${{ number_format($venta->total, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($venta->metodo_pago) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <p class="mb-0">No hay ventas en el período seleccionado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
