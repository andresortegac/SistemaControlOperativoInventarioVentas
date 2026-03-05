@extends('layouts.app')

@section('title', 'Dashboard - Licoreras')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="{{ route('ventas.punto-venta') }}" class="btn btn-primary btn-rounded">
            <i class="fas fa-cash-register me-2"></i>Nueva Venta
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <!-- Ventas Hoy -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ventas Hoy</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $configuracion->simbolo_moneda }} {{ number_format($totalVentasHoy, 0, ',', '.') }}</div>
                        <small class="text-muted">{{ $cantidadVentasHoy }} ventas</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Vendidos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Productos Vendidos Hoy</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($productosVendidosHoy, 0, ',', '.') }}</div>
                        <small class="text-muted">unidades</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas del Mes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ventas del Mes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $configuracion->simbolo_moneda }} {{ number_format($totalVentasMes, 0, ',', '.') }}</div>
                        <small class="text-muted">{{ $cantidadVentasMes }} ventas</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas Stock -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card {{ $productosStockBajo > 0 ? 'danger' : 'warning' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-{{ $productosStockBajo > 0 ? 'danger' : 'warning' }} text-uppercase mb-1">Stock Bajo</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $productosStockBajo }}</div>
                        <small class="text-muted">productos</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- More Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="text-center">
                    <h3 class="text-primary">{{ number_format($totalProductos, 0, ',', '.') }}</h3>
                    <small class="text-muted">Total Productos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="text-center">
                    <h3 class="text-success">{{ $configuracion->simbolo_moneda }} {{ number_format($valorInventario, 0, ',', '.') }}</h3>
                    <small class="text-muted">Valor Inventario</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="text-center">
                    <h3 class="text-info">{{ number_format($totalClientes, 0, ',', '.') }}</h3>
                    <small class="text-muted">Total Clientes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="text-center">
                    <h3 class="text-danger">{{ $configuracion->simbolo_moneda }} {{ number_format($gastosMes, 0, ',', '.') }}</h3>
                    <small class="text-muted">Gastos del Mes</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Ventas Recientes -->
        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Ventas Recientes
                    </h6>
                    <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-primary">Ver todas</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th># Venta</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Método</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ventasRecientes as $venta)
                            <tr>
                                <td>{{ $venta->numero_venta }}</td>
                                <td>{{ $venta->cliente ? $venta->cliente->nombre : 'Cliente General' }}</td>
                                <td class="fw-bold">{{ $configuracion->simbolo_moneda }} {{ number_format($venta->total, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $venta->metodo_pago === 'efectivo' ? 'success' : ($venta->metodo_pago === 'tarjeta' ? 'info' : 'warning') }}">
                                        {{ ucfirst($venta->metodo_pago) }}
                                    </span>
                                </td>
                                <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No hay ventas recientes</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Alertas de Stock -->
        <div class="col-lg-4">
            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Alertas de Stock
                    </h6>
                    <a href="{{ route('productos.stock-bajo') }}" class="btn btn-sm btn-danger">Ver todos</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Stock</th>
                                <th>Mínimo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alertasStock as $producto)
                            <tr>
                                <td>{{ $producto->nombre }}</td>
                                <td>
                                    <span class="badge bg-danger">{{ $producto->stock }}</span>
                                </td>
                                <td>{{ $producto->stock_minimo }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-success">
                                    <i class="fas fa-check-circle me-2"></i>No hay alertas de stock
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos Más Vendidos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy me-2"></i>Productos Más Vendidos del Mes
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Cantidad Vendida</th>
                                <th>Total Ventas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productosTop as $index => $producto)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $producto->codigo }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>
                                    <span class="badge bg-success">{{ number_format($producto->cantidad_vendida, 0, ',', '.') }}</span>
                                </td>
                                <td class="fw-bold">{{ $configuracion->simbolo_moneda }} {{ number_format($producto->total_ventas, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No hay datos disponibles</td>
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
