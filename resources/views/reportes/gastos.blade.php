@extends('layouts.app')

@section('title', 'Reporte de Gastos - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reporte de Gastos</h1>
        <a href="{{ route('reportes.index') }}" class="btn btn-secondary btn-rounded">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="table-card mb-4">
        <div class="card-body">
            <form action="{{ route('reportes.gastos') }}" method="GET" class="row g-3">
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

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card danger">
                <div class="text-center">
                    <h3 class="text-danger">${{ number_format($totalGastos, 0, ',', '.') }}</h3>
                    <small class="text-muted">Total Gastos</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card info">
                <div class="text-center">
                    <h3 class="text-info">{{ $cantidadGastos }}</h3>
                    <small class="text-muted">Cantidad de Gastos</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Gastos por Categoría
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($gastosPorCategoria as $categoria => $datos)
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h6 class="fw-bold">{{ $datos['categoria_texto'] }}</h6>
                                <p class="mb-1">Cantidad: {{ $datos['cantidad'] }}</p>
                                <p class="mb-0 text-danger">Total: ${{ number_format($datos['total'], 0, ',', '.') }}</p>
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
                <i class="fas fa-list me-2"></i>Detalle de Gastos
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th># Gasto</th>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Categoría</th>
                        <th>Monto</th>
                        <th>Método Pago</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gastos as $gasto)
                    <tr>
                        <td>{{ $gasto->numero_gasto }}</td>
                        <td>{{ $gasto->fecha_gasto->format('d/m/Y') }}</td>
                        <td>{{ $gasto->concepto }}</td>
                        <td>{{ $gasto->categoria_texto }}</td>
                        <td class="fw-bold text-danger">${{ number_format($gasto->monto, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($gasto->metodo_pago) }}</td>
                        <td>{{ $gasto->user->name }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <p class="mb-0">No hay gastos en el período seleccionado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
