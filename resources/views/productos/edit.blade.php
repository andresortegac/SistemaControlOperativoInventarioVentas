@extends('layouts.app')

@section('title', 'Editar Producto - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Editar Producto</h1>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-rounded">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-box me-2"></i>Información del Producto
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre del Producto *</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="codigo" class="form-label">Código *</label>
                                <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                                       id="codigo" name="codigo" value="{{ old('codigo', $producto->codigo) }}" required>
                                @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="codigo_barras" class="form-label">Código de Barras</label>
                                <input type="text" class="form-control @error('codigo_barras') is-invalid @enderror" 
                                       id="codigo_barras" name="codigo_barras" value="{{ old('codigo_barras', $producto->codigo_barras) }}">
                                @error('codigo_barras')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="categoria_id" class="form-label">Categoría *</label>
                                <select class="form-select @error('categoria_id') is-invalid @enderror" 
                                        id="categoria_id" name="categoria_id" required>
                                    @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="proveedor_id" class="form-label">Proveedor</label>
                                <select class="form-select @error('proveedor_id') is-invalid @enderror" 
                                        id="proveedor_id" name="proveedor_id">
                                    <option value="">Seleccionar proveedor</option>
                                    @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}" {{ old('proveedor_id', $producto->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
                                        {{ $proveedor->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('proveedor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="unidad_medida" class="form-label">Unidad de Medida *</label>
                                <select class="form-select @error('unidad_medida') is-invalid @enderror" 
                                        id="unidad_medida" name="unidad_medida" required>
                                    <option value="unidad" {{ old('unidad_medida', $producto->unidad_medida) == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                    <option value="litro" {{ old('unidad_medida', $producto->unidad_medida) == 'litro' ? 'selected' : '' }}>Litro</option>
                                    <option value="ml" {{ old('unidad_medida', $producto->unidad_medida) == 'ml' ? 'selected' : '' }}>Mililitros</option>
                                    <option value="botella" {{ old('unidad_medida', $producto->unidad_medida) == 'botella' ? 'selected' : '' }}>Botella</option>
                                    <option value="lata" {{ old('unidad_medida', $producto->unidad_medida) == 'lata' ? 'selected' : '' }}>Lata</option>
                                    <option value="caja" {{ old('unidad_medida', $producto->unidad_medida) == 'caja' ? 'selected' : '' }}>Caja</option>
                                    <option value="paquete" {{ old('unidad_medida', $producto->unidad_medida) == 'paquete' ? 'selected' : '' }}>Paquete</option>
                                </select>
                                @error('unidad_medida')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="precio_compra" class="form-label">Precio de Compra *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('precio_compra') is-invalid @enderror" 
                                           id="precio_compra" name="precio_compra" value="{{ old('precio_compra', $producto->precio_compra) }}" min="0" step="0.01" required>
                                </div>
                                @error('precio_compra')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="precio_venta" class="form-label">Precio de Venta *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('precio_venta') is-invalid @enderror" 
                                           id="precio_venta" name="precio_venta" value="{{ old('precio_venta', $producto->precio_venta) }}" min="0" step="0.01" required>
                                </div>
                                @error('precio_venta')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="stock_minimo" class="form-label">Stock Mínimo *</label>
                                <input type="number" class="form-control @error('stock_minimo') is-invalid @enderror" 
                                       id="stock_minimo" name="stock_minimo" value="{{ old('stock_minimo', $producto->stock_minimo) }}" min="0" required>
                                @error('stock_minimo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                <input type="date" class="form-control @error('fecha_vencimiento') is-invalid @enderror" 
                                       id="fecha_vencimiento" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', $producto->fecha_vencimiento?->format('Y-m-d')) }}">
                                @error('fecha_vencimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="imagen" class="form-label">Imagen del Producto</label>
                                <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                                       id="imagen" name="imagen" accept="image/*">
                                @if($producto->imagen)
                                <small class="text-muted">Imagen actual: {{ basename($producto->imagen) }}</small>
                                @endif
                                @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                            @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" {{ old('activo', $producto->activo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">
                                    Producto activo
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>Información Actual
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($producto->imagen)
                        <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-fluid rounded" style="max-height: 150px;">
                        @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                            <i class="fas fa-box fa-3x text-muted"></i>
                        </div>
                        @endif
                    </div>
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Stock Actual:</td>
                            <td class="fw-bold text-{{ $producto->stock_bajo ? 'danger' : 'success' }}">{{ $producto->stock }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ganancia:</td>
                            <td class="fw-bold text-success">${{ number_format($producto->ganancia, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Margen:</td>
                            <td>{{ number_format($producto->ganancia_porcentaje, 2) }}%</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Valor Inventario:</td>
                            <td>${{ number_format($producto->valor_inventario, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
