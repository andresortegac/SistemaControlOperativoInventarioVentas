@extends('layouts.app')

@section('title', 'Nuevo Producto - Licoreras')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nuevo Producto</h1>
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
                    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre del Producto *</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="codigo" class="form-label">Código *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                                           id="codigo" name="codigo" value="{{ old('codigo') }}" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="generarCodigo()">
                                        <i class="fas fa-random"></i>
                                    </button>
                                </div>
                                @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="codigo_barras" class="form-label">Código de Barras</label>
                                <input type="text" class="form-control @error('codigo_barras') is-invalid @enderror" 
                                       id="codigo_barras" name="codigo_barras" value="{{ old('codigo_barras') }}">
                                <small class="text-muted">Escanea o ingresa el código de barras</small>
                                @error('codigo_barras')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="categoria_id" class="form-label">Categoría *</label>
                                <select class="form-select @error('categoria_id') is-invalid @enderror" 
                                        id="categoria_id" name="categoria_id" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
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
                                    <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
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
                                    <option value="unidad" {{ old('unidad_medida') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                    <option value="litro" {{ old('unidad_medida') == 'litro' ? 'selected' : '' }}>Litro</option>
                                    <option value="ml" {{ old('unidad_medida') == 'ml' ? 'selected' : '' }}>Mililitros</option>
                                    <option value="botella" {{ old('unidad_medida') == 'botella' ? 'selected' : '' }}>Botella</option>
                                    <option value="lata" {{ old('unidad_medida') == 'lata' ? 'selected' : '' }}>Lata</option>
                                    <option value="caja" {{ old('unidad_medida') == 'caja' ? 'selected' : '' }}>Caja</option>
                                    <option value="paquete" {{ old('unidad_medida') == 'paquete' ? 'selected' : '' }}>Paquete</option>
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
                                           id="precio_compra" name="precio_compra" value="{{ old('precio_compra', 0) }}" min="0" step="0.01" required>
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
                                           id="precio_venta" name="precio_venta" value="{{ old('precio_venta', 0) }}" min="0" step="0.01" required>
                                </div>
                                @error('precio_venta')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="ganancia" class="form-label">Ganancia</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control" id="ganancia" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stock Inicial *</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                                @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stock_minimo" class="form-label">Stock Mínimo *</label>
                                <input type="number" class="form-control @error('stock_minimo') is-invalid @enderror" 
                                       id="stock_minimo" name="stock_minimo" value="{{ old('stock_minimo', 5) }}" min="0" required>
                                <small class="text-muted">Cantidad mínima antes de alerta de stock bajo</small>
                                @error('stock_minimo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                <input type="date" class="form-control @error('fecha_vencimiento') is-invalid @enderror" 
                                       id="fecha_vencimiento" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}">
                                @error('fecha_vencimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="imagen" class="form-label">Imagen del Producto</label>
                                <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                                       id="imagen" name="imagen" accept="image/*">
                                @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Producto
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
                        <i class="fas fa-lightbulb me-2"></i>Ayuda
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Código de Barras</h6>
                    <p class="small text-muted">
                        Puedes escanear el código de barras del producto usando un lector de código de barras USB. 
                        El cursor debe estar en el campo "Código de Barras".
                    </p>
                    
                    <h6 class="fw-bold mt-3">Stock Mínimo</h6>
                    <p class="small text-muted">
                        Define la cantidad mínima de stock para recibir alertas cuando el producto esté por agotarse.
                    </p>
                    
                    <h6 class="fw-bold mt-3">Precios</h6>
                    <p class="small text-muted">
                        El precio de compra es lo que pagas al proveedor. El precio de venta es lo que cobras al cliente.
                        La ganancia se calcula automáticamente.
                    </p>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Los campos marcados con * son obligatorios.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function generarCodigo() {
        const codigo = 'PROD-' + Math.random().toString(36).substring(2, 10).toUpperCase();
        document.getElementById('codigo').value = codigo;
    }
    
    function calcularGanancia() {
        const precioCompra = parseFloat(document.getElementById('precio_compra').value) || 0;
        const precioVenta = parseFloat(document.getElementById('precio_venta').value) || 0;
        const ganancia = precioVenta - precioCompra;
        document.getElementById('ganancia').value = ganancia.toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 2});
    }
    
    document.getElementById('precio_compra').addEventListener('input', calcularGanancia);
    document.getElementById('precio_venta').addEventListener('input', calcularGanancia);
    
    // Focus on barcode field for scanner
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'b') {
            e.preventDefault();
            document.getElementById('codigo_barras').focus();
        }
    });
</script>
@endpush
