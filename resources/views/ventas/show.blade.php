@extends('layouts.app')

@section('title', 'Ver Venta - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detalle de Venta</h1>
        <div>
            <a href="{{ route('ventas.ticket', $venta) }}" class="btn btn-info btn-rounded me-2" target="_blank">
                <i class="fas fa-receipt me-2"></i>Ticket
            </a>
            <a href="{{ route('ventas.pdf', $venta) }}" class="btn btn-danger btn-rounded me-2">
                <i class="fas fa-file-pdf me-2"></i>PDF
            </a>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary btn-rounded">
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
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Número:</td>
                            <td class="fw-bold">{{ $venta->numero_venta }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Fecha:</td>
                            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Cliente:</td>
                            <td>{{ $venta->cliente ? $venta->cliente->nombre : 'Cliente General' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Vendedor:</td>
                            <td>{{ $venta->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Método Pago:</td>
                            <td>{{ ucfirst($venta->metodo_pago) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estado:</td>
                            <td>
                                <span class="badge bg-{{ $venta->estado == 'pagada' ? 'success' : ($venta->estado == 'anulada' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($venta->estado) }}
                                </span>
                            </td>
                        </tr>
                        @if($venta->notas)
                        <tr>
                            <td class="text-muted">Notas:</td>
                            <td>{{ $venta->notas }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-dollar-sign me-2"></i>Totales
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Subtotal:</td>
                            <td class="text-end">${{ number_format($venta->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Descuento:</td>
                            <td class="text-end text-danger">-${{ number_format($venta->descuento, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Impuesto ({{ $configuracion->impuesto_porcentaje }}%):</td>
                            <td class="text-end">${{ number_format($venta->impuesto, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-top">
                            <td class="h5">TOTAL:</td>
                            <td class="h4 text-end text-success fw-bold">${{ number_format($venta->total, 0, ',', '.') }}</td>
                        </tr>
                        @if($venta->efectivo_recibido)
                        <tr>
                            <td class="text-muted">Efectivo Recibido:</td>
                            <td class="text-end">${{ number_format($venta->efectivo_recibido, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Cambio:</td>
                            <td class="text-end text-success">${{ number_format($venta->cambio, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-box me-2"></i>Productos Vendidos
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Precio Unit.</th>
                                <th>Cantidad</th>
                                <th>Descuento</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->nombre }}</td>
                                <td>${{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>${{ number_format($detalle->descuento, 0, ',', '.') }}</td>
                                <td class="fw-bold">${{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
