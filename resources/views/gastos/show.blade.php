@extends('layouts.app')

@section('title', 'Ver Gasto - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detalle del Gasto</h1>
        <div>
            <a href="{{ route('gastos.edit', $gasto) }}" class="btn btn-warning btn-rounded me-2">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
            <a href="{{ route('gastos.index') }}" class="btn btn-secondary btn-rounded">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-money-bill-wave me-2"></i>Información del Gasto
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" width="150">Número:</td>
                            <td class="fw-bold">{{ $gasto->numero_gasto }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Concepto:</td>
                            <td>{{ $gasto->concepto }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Categoría:</td>
                            <td>
                                <span class="badge bg-secondary">{{ $gasto->categoria_texto }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Monto:</td>
                            <td class="h4 text-danger">${{ number_format($gasto->monto, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Método de Pago:</td>
                            <td>{{ ucfirst($gasto->metodo_pago) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Fecha:</td>
                            <td>{{ $gasto->fecha_gasto->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Comprobante:</td>
                            <td>{{ $gasto->numero_comprobante ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Registrado por:</td>
                            <td>{{ $gasto->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Fecha registro:</td>
                            <td>{{ $gasto->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                    
                    @if($gasto->descripcion)
                    <hr>
                    <h6 class="fw-bold">Descripción:</h6>
                    <p class="text-muted">{{ $gasto->descripcion }}</p>
                    @endif
                    
                    @if($gasto->notas)
                    <hr>
                    <h6 class="fw-bold">Notas:</h6>
                    <p class="text-muted">{{ $gasto->notas }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
