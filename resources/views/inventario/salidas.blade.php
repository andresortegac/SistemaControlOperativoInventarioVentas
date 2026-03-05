@extends('layouts.app')

@section('title', 'Salidas de Inventario - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Salidas de Inventario</h1>
        <a href="{{ route('inventario.salidas.create') }}" class="btn btn-danger btn-rounded">
            <i class="fas fa-plus me-2"></i>Nueva Salida
        </a>
    </div>

    <div class="table-card mb-4">
        <div class="card-body">
            <form action="{{ route('inventario.salidas') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" class="form-control" name="desde" value="{{ request('desde') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" class="form-control" name="hasta" value="{{ request('hasta') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Motivo</label>
                    <select class="form-select" name="motivo">
                        <option value="">Todos</option>
                        <option value="venta" {{ request('motivo') == 'venta' ? 'selected' : '' }}>Venta</option>
                        <option value="merma" {{ request('motivo') == 'merma' ? 'selected' : '' }}>Merma</option>
                        <option value="devolucion" {{ request('motivo') == 'devolucion' ? 'selected' : '' }}>Devolución</option>
                        <option value="caducado" {{ request('motivo') == 'caducado' ? 'selected' : '' }}>Caducado</option>
                        <option value="otro" {{ request('motivo') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-arrow-up me-2"></i>Historial de Salidas
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th># Salida</th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Motivo</th>
                        <th>Descripción</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salidas as $salida)
                    <tr>
                        <td>{{ $salida->numero_salida }}</td>
                        <td>{{ $salida->fecha_salida->format('d/m/Y') }}</td>
                        <td>{{ $salida->producto->nombre }}</td>
                        <td>
                            <span class="badge bg-danger">-{{ $salida->cantidad }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $salida->motivo == 'venta' ? 'success' : ($salida->motivo == 'merma' ? 'warning' : 'secondary') }}">
                                {{ $salida->motivo_texto }}
                            </span>
                        </td>
                        <td>{{ $salida->descripcion ?? 'N/A' }}</td>
                        <td>{{ $salida->user->name }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="mb-0">No hay salidas registradas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $salidas->links() }}
        </div>
    </div>
</div>
@endsection
