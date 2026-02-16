<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Gasto;
use App\Models\Producto;
use App\Models\EntradaInventario;
use App\Models\SalidaInventario;
use App\Models\Cliente;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteVentasExport;
use App\Exports\ReporteInventarioExport;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function ventas(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->format('Y-m-d');
        $hasta = $request->hasta ?? now()->format('Y-m-d');

        $ventas = Venta::with(['user', 'cliente', 'detalles.producto'])
            ->whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->where('estado', 'pagada')
            ->orderByDesc('created_at')
            ->get();

        $totalVentas = $ventas->sum('total');
        $subtotal = $ventas->sum('subtotal');
        $descuentos = $ventas->sum('descuento');
        $impuestos = $ventas->sum('impuesto');
        $cantidadVentas = $ventas->count();
        $productosVendidos = $ventas->sum(function($v) {
            return $v->detalles->sum('cantidad');
        });

        $ventasPorDia = $ventas->groupBy(function($v) {
            return $v->created_at->format('Y-m-d');
        })->map(function($group) {
            return [
                'cantidad' => $group->count(),
                'total' => $group->sum('total'),
            ];
        });

        $ventasPorMetodo = $ventas->groupBy('metodo_pago')->map(function($group) {
            return [
                'cantidad' => $group->count(),
                'total' => $group->sum('total'),
            ];
        });

        $productosMasVendidos = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->whereBetween('ventas.created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->where('ventas.estado', 'pagada')
            ->select(
                'productos.nombre',
                'productos.codigo',
                DB::raw('SUM(detalle_ventas.cantidad) as cantidad'),
                DB::raw('SUM(detalle_ventas.subtotal) as total')
            )
            ->groupBy('productos.id', 'productos.nombre', 'productos.codigo')
            ->orderByDesc('cantidad')
            ->limit(10)
            ->get();

        return view('reportes.ventas', compact(
            'ventas',
            'totalVentas',
            'subtotal',
            'descuentos',
            'impuestos',
            'cantidadVentas',
            'productosVendidos',
            'ventasPorDia',
            'ventasPorMetodo',
            'productosMasVendidos',
            'desde',
            'hasta'
        ));
    }

    public function inventario(Request $request)
    {
        $productos = Producto::with(['categoria', 'proveedor'])
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $totalProductos = $productos->count();
        $valorInventario = $productos->sum(function($p) {
            return $p->stock * $p->precio_compra;
        });
        $valorVenta = $productos->sum(function($p) {
            return $p->stock * $p->precio_venta;
        });
        $productosStockBajo = $productos->filter(function($p) {
            return $p->stock_bajo;
        })->count();
        $productosSinStock = $productos->where('stock', 0)->count();

        $productosPorCategoria = $productos->groupBy('categoria.nombre')->map(function($group) {
            return [
                'cantidad' => $group->count(),
                'stock' => $group->sum('stock'),
                'valor' => $group->sum(function($p) {
                    return $p->stock * $p->precio_compra;
                }),
            ];
        });

        return view('reportes.inventario', compact(
            'productos',
            'totalProductos',
            'valorInventario',
            'valorVenta',
            'productosStockBajo',
            'productosSinStock',
            'productosPorCategoria'
        ));
    }

    public function gastos(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->format('Y-m-d');
        $hasta = $request->hasta ?? now()->format('Y-m-d');

        $gastos = Gasto::with('user')
            ->whereBetween('fecha_gasto', [$desde, $hasta])
            ->orderByDesc('fecha_gasto')
            ->get();

        $totalGastos = $gastos->sum('monto');
        $cantidadGastos = $gastos->count();

        $gastosPorCategoria = $gastos->groupBy('categoria')->map(function($group) {
            return [
                'cantidad' => $group->count(),
                'total' => $group->sum('monto'),
            ];
        });

        return view('reportes.gastos', compact(
            'gastos',
            'totalGastos',
            'cantidadGastos',
            'gastosPorCategoria',
            'desde',
            'hasta'
        ));
    }

    public function utilidades(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->format('Y-m-d');
        $hasta = $request->hasta ?? now()->format('Y-m-d');

        $ventas = Venta::whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->where('estado', 'pagada')
            ->get();

        $gastos = Gasto::whereBetween('fecha_gasto', [$desde, $hasta])->get();

        $totalVentas = $ventas->sum('total');
        $costoVentas = 0;
        
        foreach ($ventas as $venta) {
            foreach ($venta->detalles as $detalle) {
                $costoVentas += $detalle->producto->precio_compra * $detalle->cantidad;
            }
        }

        $gananciaBruta = $totalVentas - $costoVentas;
        $totalGastos = $gastos->sum('monto');
        $gananciaNeta = $gananciaBruta - $totalGastos;

        $margenBruto = $totalVentas > 0 ? ($gananciaBruta / $totalVentas) * 100 : 0;
        $margenNeto = $totalVentas > 0 ? ($gananciaNeta / $totalVentas) * 100 : 0;

        return view('reportes.utilidades', compact(
            'totalVentas',
            'costoVentas',
            'gananciaBruta',
            'totalGastos',
            'gananciaNeta',
            'margenBruto',
            'margenNeto',
            'desde',
            'hasta'
        ));
    }

    public function clientes(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->format('Y-m-d');
        $hasta = $request->hasta ?? now()->format('Y-m-d');

        $clientes = Cliente::with(['ventas' => function($q) use ($desde, $hasta) {
                $q->whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
                  ->where('estado', 'pagada');
            }])
            ->whereHas('ventas', function($q) use ($desde, $hasta) {
                $q->whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
                  ->where('estado', 'pagada');
            })
            ->get()
            ->map(function($cliente) {
                $cliente->total_compras = $cliente->ventas->sum('total');
                $cliente->cantidad_compras = $cliente->ventas->count();
                return $cliente;
            })
            ->sortByDesc('total_compras');

        return view('reportes.clientes', compact('clientes', 'desde', 'hasta'));
    }

    public function pdfVentas(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->format('Y-m-d');
        $hasta = $request->hasta ?? now()->format('Y-m-d');

        $ventas = Venta::with(['user', 'cliente'])
            ->whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->where('estado', 'pagada')
            ->get();

        $totalVentas = $ventas->sum('total');
        $configuracion = Configuracion::getConfiguracion();

        $pdf = PDF::loadView('reportes.pdf-ventas', compact('ventas', 'totalVentas', 'desde', 'hasta', 'configuracion'));
        
        return $pdf->download('reporte-ventas-' . $desde . '-' . $hasta . '.pdf');
    }

    public function pdfInventario()
    {
        $productos = Producto::with(['categoria'])
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $valorInventario = $productos->sum(function($p) {
            return $p->stock * $p->precio_compra;
        });

        $configuracion = Configuracion::getConfiguracion();

        $pdf = PDF::loadView('reportes.pdf-inventario', compact('productos', 'valorInventario', 'configuracion'));
        
        return $pdf->download('reporte-inventario-' . now()->format('Y-m-d') . '.pdf');
    }
}
