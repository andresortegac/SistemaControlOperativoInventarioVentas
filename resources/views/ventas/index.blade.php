@extends('layouts.app')

@section('title', 'Ventas - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ventas</h1>
        <a href="{{ route('ventas.punto-venta') }}" class="btn btn-success btn-rounded">
            <i class="fas fa-cash-register me-2"></i>Nueva Venta
        </a>
    </div>

    <div class="table-card mb-4">
        <div class="card-body">
            <form action="{{ route('ventas.index') }}" method="GET" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" class="form-control" name="desde" value="{{ request('desde') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" class="form-control" name="hasta" value="{{ request('hasta') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select class="form-select" name="estado">
                        <option value="">Todos</option>
                        <option value="pagada" {{ request('estado') == 'pagada' ? 'selected' : '' }}>Pagada</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="anulada" {{ request('estado') == 'anulada' ? 'selected' : '' }}>Anulada</option>
                    </select>
                </div>
                <div class="col-md="2">
                    <label class="form-label">Método Pago</label>
                    <select class="form-select" name="metodo_pago">
                        <option value="">Todos</option>
                        <option value="efectivo" {{ request('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="tarjeta" {{ request('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                        <option value="transferencia" {{ request('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Buscar</label>
                    <input type="text" class="form-control" name="buscar" placeholder="# venta o cliente" value="{{ request('buscar') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Ventas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalVentas, 0, ',', '.') }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Descuentos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalDescuentos, 0, ',', '.') }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tag"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-shopping-cart me-2"></i>Lista de Ventas
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
                        <th>Total</th>
                        <th>Método</th>
                        <th>Estado</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                    <tr>
                        <td>{{ $venta->numero_venta }}</td>
                        <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $venta->cliente ? $venta->cliente->nombre : 'Cliente General' }}</td>
                        <td>{{ $venta->detalles->sum('cantidad') }}</td>
                        <td class="fw-bold">${{ number_format($venta->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $venta->metodo_pago == 'efectivo' ? 'success' : ($venta->metodo_pago == 'tarjeta' ? 'info' : 'warning') }}">
                                {{ ucfirst($venta->metodo_pago) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $venta->estado == 'pagada' ? 'success' : ($venta->estado == 'anulada' ? 'danger' : 'warning') }}">
                                {{ ucfirst($venta->estado) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('ventas.ticket', $venta) }}" class="btn btn-sm btn-outline-info" title="Ticket" target="_blank">
                                    <i class="fas fa-receipt"></i>
                                </a>
                                @if($venta->estado != 'anulada')
                                <form action="{{ route('ventas.anular', $venta) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Anular esta venta? El stock será restaurado.')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Anular">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="mb-0">No hay ventas registradas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $ventas->links() }}
        </div>
    </div>
</div>
@endsection
