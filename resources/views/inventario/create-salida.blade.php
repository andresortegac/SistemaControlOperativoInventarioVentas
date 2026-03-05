@extends('layouts.app')

@section('title', 'Nueva Salida - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nueva Salida de Inventario</h1>
        <a href="{{ route('inventario.salidas') }}" class="btn btn-secondary btn-rounded">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-arrow-up me-2"></i>Registrar Salida
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventario.salidas.store') }}" method="POST">
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
                                <label for="cantidad" class="form-label">Cantidad *</label>
                                <input type="number" class="form-control @error('cantidad') is-invalid @enderror" 
                                       id="cantidad" name="cantidad" value="{{ old('cantidad', 1) }}" min="1" required>
                                @error('cantidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="motivo" class="form-label">Motivo *</label>
                                <select class="form-select @error('motivo') is-invalid @enderror" 
                                        id="motivo" name="motivo" required>
                                    <option value="">Seleccionar motivo</option>
                                    <option value="merma" {{ old('motivo') == 'merma' ? 'selected' : '' }}>Merma</option>
                                    <option value="devolucion" {{ old('motivo') == 'devolucion' ? 'selected' : '' }}>Devolución</option>
                                    <option value="caducado" {{ old('motivo') == 'caducado' ? 'selected' : '' }}>Producto Caducado</option>
                                    <option value="otro" {{ old('motivo') == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('motivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_salida" class="form-label">Fecha *</label>
                                <input type="date" class="form-control @error('fecha_salida') is-invalid @enderror" 
                                       id="fecha_salida" name="fecha_salida" value="{{ old('fecha_salida', date('Y-m-d')) }}" required>
                                @error('fecha_salida')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="2">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
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
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Importante:</strong> Esta acción disminuirá el stock del producto seleccionado.
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('inventario.salidas') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-save me-2"></i>Registrar Salida
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
