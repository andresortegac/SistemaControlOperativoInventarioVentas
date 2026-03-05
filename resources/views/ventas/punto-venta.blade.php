@extends('layouts.app')

@section('title', 'Punto de Venta - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Productos y Buscador -->
        <div class="col-lg-8">
            <div class="table-card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-barcode me-2"></i>Escáner de Código de Barras
                        </h6>
                        <span class="badge bg-success" id="scanner-status">
                            <i class="fas fa-check-circle me-1"></i>Listo para escanear
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="input-group input-group-lg mb-3">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-barcode"></i>
                        </span>
                        <input type="text" class="form-control form-control-lg" id="barcode-input" 
                               placeholder="Escanea el código de barras o escribe el código del producto..." 
                               autocomplete="off">
                        <button class="btn btn-primary" type="button" onclick="buscarProducto()">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                    </div>
                    
                    <div class="text-center py-3 border rounded bg-light position-relative overflow-hidden" id="scanner-area">
                        <div class="scanner-line"></div>
                        <i class="fas fa-barcode fa-3x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Coloca el cursor en el campo y escanea el producto</p>
                        <small class="text-muted">o presiona Ctrl+B para enfocar</small>
                    </div>
                </div>
            </div>
            
            <!-- Carrito -->
            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Carrito de Compras
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="limpiarCarrito()">
                        <i class="fas fa-trash me-2"></i>Limpiar
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="carrito-table">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th width="120">Precio</th>
                                <th width="100">Cant.</th>
                                <th width="120">Subtotal</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="carrito-body">
                            <tr id="empty-cart">
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                    <p class="mb-0">El carrito está vacío</p>
                                    <small>Escanea productos para agregarlos</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Resumen y Pago -->
        <div class="col-lg-4">
            <div class="table-card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-receipt me-2"></i>Resumen de Venta
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Cliente -->
                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <select class="form-select" id="cliente-select">
                            <option value="">Cliente General</option>
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Totales -->
                    <div class="bg-light p-3 rounded mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span class="fw-bold" id="subtotal">$ 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Descuento:</span>
                            <div class="input-group input-group-sm" style="width: 120px;">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="descuento" value="0" min="0" onchange="calcularTotales()">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Impuesto ({{ $configuracion->impuesto_porcentaje }}%):</span>
                            <span class="fw-bold" id="impuesto">$ 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="h5">TOTAL:</span>
                            <span class="h3 text-primary fw-bold" id="total">$ 0</span>
                        </div>
                    </div>
                    
                    <!-- Método de Pago -->
                    <div class="mb-3">
                        <label class="form-label">Método de Pago</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="metodo_pago" id="pago-efectivo" value="efectivo" checked onchange="toggleEfectivo()">
                            <label class="btn btn-outline-primary" for="pago-efectivo">
                                <i class="fas fa-money-bill-wave"></i> Efectivo
                            </label>
                            
                            <input type="radio" class="btn-check" name="metodo_pago" id="pago-tarjeta" value="tarjeta" onchange="toggleEfectivo()">
                            <label class="btn btn-outline-primary" for="pago-tarjeta">
                                <i class="fas fa-credit-card"></i> Tarjeta
                            </label>
                            
                            <input type="radio" class="btn-check" name="metodo_pago" id="pago-transferencia" value="transferencia" onchange="toggleEfectivo()">
                            <label class="btn btn-outline-primary" for="pago-transferencia">
                                <i class="fas fa-mobile-alt"></i> Transferencia
                            </label>
                        </div>
                    </div>
                    
                    <!-- Efectivo Recibido -->
                    <div class="mb-3" id="efectivo-section">
                        <label class="form-label">Efectivo Recibido</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control form-control-lg" id="efectivo-recibido" 
                                   placeholder="0" oninput="calcularCambio()">
                        </div>
                        <div class="mt-2 d-flex justify-content-between align-items-center">
                            <span>Cambio:</span>
                            <span class="h4 text-success fw-bold" id="cambio">$ 0</span>
                        </div>
                    </div>
                    
                    <!-- Notas -->
                    <div class="mb-3">
                        <label class="form-label">Notas</label>
                        <textarea class="form-control" id="notas" rows="2"></textarea>
                    </div>
                    
                    <!-- Botón Finalizar -->
                    <button type="button" class="btn btn-success btn-lg w-100" onclick="finalizarVenta()" id="btn-finalizar" disabled>
                        <i class="fas fa-check-circle me-2"></i>Finalizar Venta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Éxito -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <i class="fas fa-check-circle text-success fa-5x mb-3"></i>
                <h3 class="mb-2">¡Venta Exitosa!</h3>
                <p class="text-muted" id="venta-numero"></p>
                <div class="bg-light p-3 rounded mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Total:</span>
                        <span class="h4 fw-bold" id="venta-total"></span>
                    </div>
                    <div class="d-flex justify-content-between" id="venta-cambio-row">
                        <span>Cambio:</span>
                        <span class="h5 text-success" id="venta-cambio"></span>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" onclick="imprimirTicket()">
                        <i class="fas fa-print me-2"></i>Imprimir Ticket
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="nuevaVenta()">
                        <i class="fas fa-plus me-2"></i>Nueva Venta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let carrito = [];
let impuestoPorcentaje = {{ $configuracion->impuesto_porcentaje }};
let ventaId = null;

// Focus on barcode input on load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('barcode-input').focus();
});

// Barcode scanner handler
document.getElementById('barcode-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        buscarProducto();
    }
});

// Ctrl+B shortcut
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'b') {
        e.preventDefault();
        document.getElementById('barcode-input').focus();
    }
});

function buscarProducto() {
    const codigo = document.getElementById('barcode-input').value.trim();
    if (!codigo) return;
    
    fetch(`{{ route('ventas.agregar-producto') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"').content
        },
        body: JSON.stringify({ codigo: codigo })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            agregarAlCarrito(data.data);
            document.getElementById('barcode-input').value = '';
            document.getElementById('barcode-input').focus();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al buscar el producto');
    });
}

function agregarAlCarrito(producto) {
    const existente = carrito.find(p => p.id === producto.id);
    
    if (existente) {
        if (existente.cantidad < producto.stock) {
            existente.cantidad++;
        } else {
            alert('Stock insuficiente');
            return;
        }
    } else {
        carrito.push({
            id: producto.id,
            nombre: producto.nombre,
            codigo: producto.codigo,
            precio: parseFloat(producto.precio_venta),
            cantidad: 1,
            stock: producto.stock
        });
    }
    
    actualizarCarrito();
}

function actualizarCarrito() {
    const tbody = document.getElementById('carrito-body');
    
    if (carrito.length === 0) {
        tbody.innerHTML = `
            <tr id="empty-cart">
                <td colspan="5" class="text-center py-4 text-muted">
                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                    <p class="mb-0">El carrito está vacío</p>
                    <small>Escanea productos para agregarlos</small>
                </td>
            </tr>
        `;
        document.getElementById('btn-finalizar').disabled = true;
    } else {
        tbody.innerHTML = carrito.map((item, index) => `
            <tr>
                <td>
                    <div class="fw-bold">${item.nombre}</div>
                    <small class="text-muted">${item.codigo}</small>
                </td>
                <td>$ ${item.precio.toLocaleString('es-CO')}</td>
                <td>
                    <div class="input-group input-group-sm">
                        <button class="btn btn-outline-secondary" onclick="cambiarCantidad(${index}, -1)">-</button>
                        <input type="text" class="form-control text-center" value="${item.cantidad}" readonly>
                        <button class="btn btn-outline-secondary" onclick="cambiarCantidad(${index}, 1)">+</button>
                    </div>
                </td>
                <td class="fw-bold">$ ${(item.precio * item.cantidad).toLocaleString('es-CO')}</td>
                <td>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarDelCarrito(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
        document.getElementById('btn-finalizar').disabled = false;
    }
    
    calcularTotales();
}

function cambiarCantidad(index, cambio) {
    const item = carrito[index];
    const nuevaCantidad = item.cantidad + cambio;
    
    if (nuevaCantidad > 0 && nuevaCantidad <= item.stock) {
        item.cantidad = nuevaCantidad;
        actualizarCarrito();
    } else if (nuevaCantidad > item.stock) {
        alert('Stock insuficiente');
    }
}

function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    actualizarCarrito();
}

function limpiarCarrito() {
    if (carrito.length > 0 && confirm('¿Estás seguro de limpiar el carrito?')) {
        carrito = [];
        actualizarCarrito();
    }
}

function calcularTotales() {
    const subtotal = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    const descuento = parseFloat(document.getElementById('descuento').value) || 0;
    const impuesto = (subtotal - descuento) * (impuestoPorcentaje / 100);
    const total = subtotal - descuento + impuesto;
    
    document.getElementById('subtotal').textContent = '$ ' + subtotal.toLocaleString('es-CO');
    document.getElementById('impuesto').textContent = '$ ' + impuesto.toLocaleString('es-CO');
    document.getElementById('total').textContent = '$ ' + total.toLocaleString('es-CO');
    
    calcularCambio();
}

function toggleEfectivo() {
    const metodo = document.querySelector('input[name="metodo_pago"]:checked').value;
    const efectivoSection = document.getElementById('efectivo-section');
    
    if (metodo === 'efectivo') {
        efectivoSection.style.display = 'block';
    } else {
        efectivoSection.style.display = 'none';
    }
}

function calcularCambio() {
    const total = parseFloat(document.getElementById('total').textContent.replace(/[^0-9]/g, '')) || 0;
    const recibido = parseFloat(document.getElementById('efectivo-recibido').value) || 0;
    const cambio = recibido - total;
    
    document.getElementById('cambio').textContent = '$ ' + (cambio > 0 ? cambio : 0).toLocaleString('es-CO');
}

function finalizarVenta() {
    if (carrito.length === 0) {
        alert('El carrito está vacío');
        return;
    }
    
    const metodoPago = document.querySelector('input[name="metodo_pago"]:checked').value;
    const total = parseFloat(document.getElementById('total').textContent.replace(/[^0-9]/g, '')) || 0;
    
    if (metodoPago === 'efectivo') {
        const recibido = parseFloat(document.getElementById('efectivo-recibido').value) || 0;
        if (recibido < total) {
            alert('El efectivo recibido es menor al total');
            return;
        }
    }
    
    const data = {
        cliente_id: document.getElementById('cliente-select').value,
        metodo_pago: metodoPago,
        efectivo_recibido: metodoPago === 'efectivo' ? parseFloat(document.getElementById('efectivo-recibido').value) : null,
        descuento: parseFloat(document.getElementById('descuento').value) || 0,
        notas: document.getElementById('notas').value,
        productos: carrito.map(item => ({
            id: item.id,
            cantidad: item.cantidad,
            precio: item.precio
        }))
    };
    
    fetch('{{ route('ventas.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            ventaId = data.data.venta_id;
            document.getElementById('venta-numero').textContent = 'Venta #' + data.data.numero_venta;
            document.getElementById('venta-total').textContent = '$ ' + data.data.total.toLocaleString('es-CO');
            document.getElementById('venta-cambio').textContent = '$ ' + (data.data.cambio || 0).toLocaleString('es-CO');
            
            if (data.data.cambio > 0) {
                document.getElementById('venta-cambio-row').style.display = 'flex';
            } else {
                document.getElementById('venta-cambio-row').style.display = 'none';
            }
            
            new bootstrap.Modal(document.getElementById('successModal')).show();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la venta');
    });
}

function imprimirTicket() {
    if (ventaId) {
        window.open(`{{ url('ventas') }}/${ventaId}/ticket`, '_blank');
    }
}

function nuevaVenta() {
    carrito = [];
    ventaId = null;
    document.getElementById('descuento').value = 0;
    document.getElementById('efectivo-recibido').value = '';
    document.getElementById('notas').value = '';
    document.getElementById('cliente-select').value = '';
    
    actualizarCarrito();
    bootstrap.Modal.getInstance(document.getElementById('successModal')).hide();
    document.getElementById('barcode-input').focus();
}
</script>
@endpush
