@extends('layouts.app')

@section('title', 'Nuevo Gasto - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nuevo Gasto</h1>
        <a href="{{ route('gastos.index') }}" class="btn btn-secondary btn-rounded">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-money-bill-wave me-2"></i>Registrar Gasto
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('gastos.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="concepto" class="form-label">Concepto *</label>
                                <input type="text" class="form-control @error('concepto') is-invalid @enderror" 
                                       id="concepto" name="concepto" value="{{ old('concepto') }}" required>
                                @error('concepto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="categoria" class="form-label">Categoría *</label>
                                <select class="form-select @error('categoria') is-invalid @enderror" 
                                        id="categoria" name="categoria" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $key => $value)
                                    <option value="{{ $key }}" {{ old('categoria') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="monto" class="form-label">Monto *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('monto') is-invalid @enderror" 
                                           id="monto" name="monto" value="{{ old('monto') }}" step="0.01" min="0" required>
                                </div>
                                @error('monto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="metodo_pago" class="form-label">Método de Pago *</label>
                                <select class="form-select @error('metodo_pago') is-invalid @enderror" 
                                        id="metodo_pago" name="metodo_pago" required>
                                    <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                                    <option value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                                    <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                                </select>
                                @error('metodo_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="fecha_gasto" class="form-label">Fecha *</label>
                                <input type="date" class="form-control @error('fecha_gasto') is-invalid @enderror" 
                                       id="fecha_gasto" name="fecha_gasto" value="{{ old('fecha_gasto', date('Y-m-d')) }}" required>
                                @error('fecha_gasto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="numero_comprobante" class="form-label">Número de Comprobante</label>
                            <input type="text" class="form-control @error('numero_comprobante') is-invalid @enderror" 
                                   id="numero_comprobante" name="numero_comprobante" value="{{ old('numero_comprobante') }}">
                            @error('numero_comprobante')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('gastos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-save me-2"></i>Registrar Gasto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
