@extends('layouts.app')

@section('title', 'Configuración - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Configuración</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog me-2"></i>Configuración General
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('configuracion.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <h6 class="fw-bold text-primary mb-3">Información del Negocio</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre_negocio" class="form-label">Nombre del Negocio *</label>
                                <input type="text" class="form-control" id="nombre_negocio" name="nombre_negocio" 
                                       value="{{ old('nombre_negocio', $configuracion->nombre_negocio) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="slogan" class="form-label">Slogan</label>
                                <input type="text" class="form-control" id="slogan" name="slogan" 
                                       value="{{ old('slogan', $configuracion->slogan) }}">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="nit" class="form-label">NIT</label>
                                <input type="text" class="form-control" id="nit" name="nit" 
                                       value="{{ old('nit', $configuracion->nit) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" 
                                       value="{{ old('telefono', $configuracion->telefono) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $configuracion->email) }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2">{{ old('direccion', $configuracion->direccion) }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            @if($configuracion->logo)
                            <div class="mt-2">
                                <img src="{{ asset($configuracion->logo) }}" alt="Logo" height="60">
                            </div>
                            @endif
                        </div>
                        
                        <hr class="my-4">
                        <h6 class="fw-bold text-primary mb-3">Configuración de Facturación</h6>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="impuesto_porcentaje" class="form-label">% Impuesto *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="impuesto_porcentaje" name="impuesto_porcentaje" 
                                           value="{{ old('impuesto_porcentaje', $configuracion->impuesto_porcentaje) }}" step="0.01" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="moneda" class="form-label">Moneda *</label>
                                <input type="text" class="form-control" id="moneda" name="moneda" 
                                       value="{{ old('moneda', $configuracion->moneda) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="simbolo_moneda" class="form-label">Símbolo *</label>
                                <input type="text" class="form-control" id="simbolo_moneda" name="simbolo_moneda" 
                                       value="{{ old('simbolo_moneda', $configuracion->simbolo_moneda) }}" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="decimales" class="form-label">Decimales *</label>
                                <input type="number" class="form-control" id="decimales" name="decimales" 
                                       value="{{ old('decimales', $configuracion->decimales) }}" min="0" max="4" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="formato_fecha" class="form-label">Formato de Fecha *</label>
                                <input type="text" class="form-control" id="formato_fecha" name="formato_fecha" 
                                       value="{{ old('formato_fecha', $configuracion->formato_fecha) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="stock_alerta" class="form-label">Alerta Stock *</label>
                                <input type="number" class="form-control" id="stock_alerta" name="stock_alerta" 
                                       value="{{ old('stock_alerta', $configuracion->stock_alerta) }}" min="0" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="factura_con_impuesto" name="factura_con_impuesto" 
                                       {{ $configuracion->factura_con_impuesto ? 'checked' : '' }}>
                                <label class="form-check-label" for="factura_con_impuesto">
                                    Incluir impuesto en las facturas
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="mensaje_factura" class="form-label">Mensaje en Factura</label>
                            <textarea class="form-control" id="mensaje_factura" name="mensaje_factura" rows="2">{{ old('mensaje_factura', $configuracion->mensaje_factura) }}</textarea>
                            <small class="text-muted">Este mensaje aparecerá al final de cada factura</small>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="table-card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>Información
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">
                        Esta configuración afecta a todo el sistema. Los cambios se aplicarán inmediatamente.
                    </p>
                    
                    <h6 class="fw-bold mt-3">Formatos de Fecha</h6>
                    <ul class="small text-muted">
                        <li><code>d/m/Y</code> = 31/12/2024</li>
                        <li><code>m/d/Y</code> = 12/31/2024</li>
                        <li><code>Y-m-d</code> = 2024-12-31</li>
                    </ul>
                </div>
            </div>
            
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-database me-2"></i>Backup
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">
                        Realiza un backup de la base de datos periódicamente para proteger tu información.
                    </p>
                    <a href="{{ route('configuracion.backup') }}" class="btn btn-warning w-100">
                        <i class="fas fa-download me-2"></i>Generar Backup
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
