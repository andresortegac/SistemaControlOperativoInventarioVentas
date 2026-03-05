@extends('layouts.app')

@section('title', 'Nueva Entrada - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nueva Entrada de Inventario</h1>
        <a href="{{ route('inventario.entradas') }}" class="btn btn-secondary btn-rounded">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-arrow-down me-2"></i>Registrar Entrada
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventario.entradas.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="producto_id" class="form-label">Producto *</label>
                                <select class="form-select @error('producto_id') is-invalid @enderror" 
                                        id="producto_id" name="producto_id" required>
                                    <option value="">Seleccionar producto</option>
                                    @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                        {{ $producto->nombre }} (Stock: {{ $producto->stock }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('producto_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="proveedor_id" class="form-label">Proveedor</label>
                                <select class="form-select @error('proveedor_id') is-invalid @enderror" 
                                        id="proveedor_id" name="proveedor_id">
                                    <option value="">Seleccionar proveedor</option>
                                    @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                        {{ $proveedor->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('proveedor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="cantidad" class="form-label">Cantidad *</label>
                                <input type="number" class="form-control @error('cantidad') is-invalid @enderror" 
                                       id="cantidad" name="cantidad" value="{{ old('cantidad', 1) }}" min="1" required>
                                @error('cantidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="costo_unitario" class="form-label">Costo Unitario</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('costo_unitario') is-invalid @enderror" 
                                           id="costo_unitario" name="costo_unitario" value="{{ old('costo_unitario') }}" step="0.01" min="0">
                                </div>
                                @error('costo_unitario')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="fecha_entrada" class="form-label">Fecha *</label>
                                <input type="date" class="form-control @error('fecha_entrada') is-invalid @enderror" 
                                       id="fecha_entrada" name="fecha_entrada" value="{{ old('fecha_entrada', date('Y-m-d')) }}" required>
                                @error('fecha_entrada')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="numero_factura" class="form-label">Número de Factura</label>
                                <input type="text" class="form-control @error('numero_factura') is-invalid @enderror" 
                                       id="numero_factura" name="numero_factura" value="{{ old('numero_factura') }}">
                                @error('numero_factura')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="motivo" class="form-label">Motivo</label>
                            <textarea class="form-control @error('motivo') is-invalid @enderror" 
                                      id="motivo" name="motivo" rows="2">{{ old('motivo') }}</textarea>
                            @error('motivo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas</label>
                            <textarea class="form-control @error('notas') is-invalid @enderror" 
                                      id="notas" name="notas" rows="2">{{ old('notas') }}</textarea>
                            @error('notas')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('inventario.entradas') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Registrar Entrada
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
