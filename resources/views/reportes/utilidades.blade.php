@extends('layouts.app')

@section('title', 'Reporte de Utilidades - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reporte de Utilidades</h1>
        <a href="{{ route('reportes.index') }}" class="btn btn-secondary btn-rounded">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="table-card mb-4">
        <div class="card-body">
            <form action="{{ route('reportes.utilidades') }}" method="GET" class="row g-3">
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
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="text-center">
                    <h3 class="text-success">${{ number_format($totalVentas, 0, ',', '.') }}</h3>
                    <small class="text-muted">Total Ventas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card danger">
                <div class="text-center">
                    <h3 class="text-danger">${{ number_format($costoVentas, 0, ',', '.') }}</h3>
                    <small class="text-muted">Costo de Ventas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <div class="text-center">
                    <h3 class="text-info">${{ number_format($gananciaBruta, 0, ',', '.') }}</h3>
                    <small class="text-muted">Ganancia Bruta</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card primary">
                <div class="text-center">
                    <h3 class="text-primary">${{ number_format($gananciaNeta, 0, ',', '.') }}</h3>
                    <small class="text-muted">Ganancia Neta</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Resumen Financiero</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Total Ventas:</td>
                            <td class="text-end fw-bold">${{ number_format($totalVentas, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Costo de Ventas:</td>
                            <td class="text-end text-danger">-${{ number_format($costoVentas, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-top">
                            <td class="fw-bold">Ganancia Bruta:</td>
                            <td class="text-end fw-bold text-info">${{ number_format($gananciaBruta, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Gastos:</td>
                            <td class="text-end text-danger">-${{ number_format($totalGastos, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-top">
                            <td class="fw-bold h5">Ganancia Neta:</td>
                            <td class="text-end fw-bold h5 text-{{ $gananciaNeta >= 0 ? 'success' : 'danger' }}">
                                ${{ number_format($gananciaNeta, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Margen Bruto:</td>
                            <td class="text-end">{{ number_format($margenBruto, 2) }}%</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Margen Neto:</td>
                            <td class="text-end">{{ number_format($margenNeto, 2) }}%</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Indicadores</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Margen Bruto</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ min($margenBruto, 100) }}%">
                                {{ number_format($margenBruto, 2) }}%
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Margen Neto</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-{{ $margenNeto >= 0 ? 'success' : 'danger' }}" role="progressbar" style="width: {{ min(abs($margenNeto), 100) }}%">
                                {{ number_format($margenNeto, 2) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
