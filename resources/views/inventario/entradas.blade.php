@extends('layouts.app')

@section('title', 'Entradas de Inventario - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Entradas de Inventario</h1>
        <a href="{{ route('inventario.entradas.create') }}" class="btn btn-primary btn-rounded">
            <i class="fas fa-plus me-2"></i>Nueva Entrada
        </a>
    </div>

    <div class="table-card mb-4">
        <div class="card-body">
            <form action="{{ route('inventario.entradas') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Desde</label>
                    <input type="date" class="form-control" name="desde" value="{{ request('desde') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hasta</label>
                    <input type="date" class="form-control" name="hasta" value="{{ request('hasta') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-arrow-down me-2"></i>Historial de Entradas
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th># Entrada</th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Costo Unit.</th>
                        <th>Costo Total</th>
                        <th>Proveedor</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entradas as $entrada)
                    <tr>
                        <td>{{ $entrada->numero_entrada }}</td>
                        <td>{{ $entrada->fecha_entrada->format('d/m/Y') }}</td>
                        <td>{{ $entrada->producto->nombre }}</td>
                        <td>
                            <span class="badge bg-success">+{{ $entrada->cantidad }}</span>
                        </td>
                        <td>${{ $entrada->costo_unitario ? number_format($entrada->costo_unitario, 0, ',', '.') : 'N/A' }}</td>
                        <td>${{ $entrada->costo_total ? number_format($entrada->costo_total, 0, ',', '.') : 'N/A' }}</td>
                        <td>{{ $entrada->proveedor->nombre ?? 'N/A' }}</td>
                        <td>{{ $entrada->user->name }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="mb-0">No hay entradas registradas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $entradas->links() }}
        </div>
    </div>
</div>
@endsection
