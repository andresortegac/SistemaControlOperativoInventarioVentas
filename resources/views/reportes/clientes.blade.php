@extends('layouts.app')

@section('title', 'Reporte de Clientes - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reporte de Clientes</h1>
        <a href="{{ route('reportes.index') }}" class="btn btn-secondary btn-rounded">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="table-card mb-4">
        <div class="card-body">
            <form action="{{ route('reportes.clientes') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Desde</label>
                    <input type="date" class="form-control" name="desde" value="{{ $desde }}">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Hasta</label>
                    <input type="date" class="form-control" name="hasta" value="{{ $hasta }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Generar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users me-2"></i>Clientes Frecuentes
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Teléfono</th>
                        <th>Compras</th>
                        <th>Total Comprado</th>
                        <th>Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $index => $cliente)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                    {{ substr($cliente->nombre, 0, 1) }}
                                </div>
                                <div class="fw-bold">{{ $cliente->nombre }}</div>
                            </div>
                        </td>
                        <td>{{ $cliente->documento ?? 'N/A' }}</td>
                        <td>{{ $cliente->telefono ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $cliente->cantidad_compras }}</span>
                        </td>
                        <td class="fw-bold text-success">${{ number_format($cliente->total_compras, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i>{{ number_format($cliente->puntos_fidelidad, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <p class="mb-0">No hay clientes con compras en el período seleccionado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
