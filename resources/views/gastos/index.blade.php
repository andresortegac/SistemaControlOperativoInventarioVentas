@extends('layouts.app')

@section('title', 'Gastos - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gastos</h1>
        <a href="{{ route('gastos.create') }}" class="btn btn-danger btn-rounded">
            <i class="fas fa-plus me-2"></i>Nuevo Gasto
        </a>
    </div>

    <div class="table-card mb-4">
        <div class="card-body">
            <form action="{{ route('gastos.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" class="form-control" name="desde" value="{{ request('desde') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" class="form-control" name="hasta" value="{{ request('hasta') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Categoría</label>
                    <select class="form-select" name="categoria">
                        <option value="">Todas</option>
                        @foreach($categorias as $key => $value)
                        <option value="{{ $key }}" {{ request('categoria') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Gastos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalGastos, 0, ',', '.') }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Lista de Gastos
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
                        <th width="100">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gastos as $gasto)
                    <tr>
                        <td>{{ $gasto->numero_gasto }}</td>
                        <td>{{ $gasto->fecha_gasto->format('d/m/Y') }}</td>
                        <td>{{ $gasto->concepto }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $gasto->categoria_texto }}</span>
                        </td>
                        <td class="fw-bold text-danger">${{ number_format($gasto->monto, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($gasto->metodo_pago) }}</td>
                        <td>{{ $gasto->user->name }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('gastos.show', $gasto) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('gastos.edit', $gasto) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="mb-0">No hay gastos registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $gastos->links() }}
        </div>
    </div>
</div>
@endsection
