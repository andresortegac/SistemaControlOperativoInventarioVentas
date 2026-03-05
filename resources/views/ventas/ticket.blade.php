<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket {{ $venta->numero_venta }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            padding: 5mm;
        }
        .center {
            text-align: center;
        }
        .header {
            margin-bottom: 10px;
        }
        .header h2 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .info {
            margin-bottom: 10px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 2px 0;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .items th, .items td {
            text-align: left;
            padding: 3px 0;
        }
        .items th {
            border-bottom: 1px solid #000;
        }
        .items .right {
            text-align: right;
        }
        .totals {
            width: 100%;
            margin-top: 10px;
        }
        .totals td {
            padding: 2px 0;
        }
        .totals .right {
            text-align: right;
        }
        .totals .total {
            font-size: 14px;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
        }
        .barcode {
            text-align: center;
            margin: 10px 0;
        }
        @media print {
            body {
                width: 80mm;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header center">
        <h2>{{ $configuracion->nombre_negocio }}</h2>
        @if($configuracion->slogan)
        <p>{{ $configuracion->slogan }}</p>
        @endif
        @if($configuracion->direccion)
        <p>{{ $configuracion->direccion }}</p>
        @endif
        @if($configuracion->telefono)
        <p>Tel: {{ $configuracion->telefono }}</p>
        @endif
    </div>

    <div class="divider"></div>

    <div class="info">
        <table>
            <tr>
                <td>Ticket:</td>
                <td>{{ $venta->numero_venta }}</td>
            </tr>
            <tr>
                <td>Fecha:</td>
                <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Cliente:</td>
                <td>{{ $venta->cliente ? $venta->cliente->nombre : 'Cliente General' }}</td>
            </tr>
            <tr>
                <td>Vendedor:</td>
                <td>{{ $venta->user->name }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <table class="items">
        <thead>
            <tr>
                <th>Producto</th>
                <th class="right">Cant.</th>
                <th class="right">Precio</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->nombre }}</td>
                <td class="right">{{ $detalle->cantidad }}</td>
                <td class="right">${{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                <td class="right">${{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="totals">
        <tr>
            <td>Subtotal:</td>
            <td class="right">${{ number_format($venta->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($venta->descuento > 0)
        <tr>
            <td>Descuento:</td>
            <td class="right">-${{ number_format($venta->descuento, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td>Impuesto ({{ $configuracion->impuesto_porcentaje }}%):</td>
            <td class="right">${{ number_format($venta->impuesto, 0, ',', '.') }}</td>
        </tr>
        <tr class="total">
            <td>TOTAL:</td>
            <td class="right">${{ number_format($venta->total, 0, ',', '.') }}</td>
        </tr>
        @if($venta->efectivo_recibido)
        <tr>
            <td>Efectivo:</td>
            <td class="right">${{ number_format($venta->efectivo_recibido, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Cambio:</td>
            <td class="right">${{ number_format($venta->cambio, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td>Método de Pago:</td>
            <td class="right">{{ ucfirst($venta->metodo_pago) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="footer">
        @if($configuracion->mensaje_factura)
        <p>{{ $configuracion->mensaje_factura }}</p>
        @endif
        <p>¡Gracias por su compra!</p>
        <p>---</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px;">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px; margin-left: 10px;">
            Cerrar
        </button>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
