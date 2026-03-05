<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\Gasto;
use App\Models\EntradaInventario;
use App\Models\Cliente;
use App\Models\Configuracion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        // Estadísticas de hoy
        $ventasHoy = Venta::whereDate('created_at', $hoy)->where('estado', 'pagada');
        $totalVentasHoy = $ventasHoy->sum('total');
        $cantidadVentasHoy = $ventasHoy->count();
        $productosVendidosHoy = DB::table('detalle_ventas')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->whereDate('ventas.created_at', $hoy)
            ->where('ventas.estado', 'pagada')
            ->sum('detalle_ventas.cantidad');

        // Estadísticas del mes
        $ventasMes = Venta::whereBetween('created_at', [$inicioMes, $finMes])->where('estado', 'pagada');
        $totalVentasMes = $ventasMes->sum('total');
        $cantidadVentasMes = $ventasMes->count();

        // Gastos
        $gastosHoy = Gasto::whereDate('fecha_gasto', $hoy)->sum('monto');
        $gastosMes = Gasto::whereBetween('fecha_gasto', [$inicioMes, $finMes])->sum('monto');

        // Productos
        $totalProductos = Producto::where('activo', true)->count();
        $productosStockBajo = Producto::where('activo', true)->stockBajo()->count();
        $productosSinStock = Producto::where('activo', true)->where('stock', 0)->count();

        // Valor del inventario
        $valorInventario = Producto::where('activo', true)
            ->select(DB::raw('SUM(stock * precio_compra) as total'))
            ->first()->total ?? 0;

        // Clientes
        $totalClientes = Cliente::where('activo', true)->count();
        $clientesNuevosMes = Cliente::whereBetween('created_at', [$inicioMes, $finMes])->count();

        // Ventas por día del mes actual (para gráfico)
        $ventasPorDia = Venta::select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as cantidad')
            )
            ->whereBetween('created_at', [$inicioMes, $finMes])
            ->where('estado', 'pagada')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Productos más vendidos
        $productosTop = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->whereBetween('ventas.created_at', [$inicioMes, $finMes])
            ->where('ventas.estado', 'pagada')
            ->select(
                'productos.id',
                'productos.nombre',
                'productos.codigo',
                DB::raw('SUM(detalle_ventas.cantidad) as cantidad_vendida'),
                DB::raw('SUM(detalle_ventas.subtotal) as total_ventas')
            )
            ->groupBy('productos.id', 'productos.nombre', 'productos.codigo')
            ->orderByDesc('cantidad_vendida')
            ->limit(5)
            ->get();

        // Ventas recientes
        $ventasRecientes = Venta::with(['user', 'cliente'])
            ->where('estado', 'pagada')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Productos con stock bajo
        $alertasStock = Producto::with('categoria')
            ->where('activo', true)
            ->stockBajo()
            ->orderBy('stock')
            ->limit(10)
            ->get();

        $configuracion = Configuracion::getConfiguracion();

        return view('dashboard.index', compact(
            'totalVentasHoy',
            'cantidadVentasHoy',
            'productosVendidosHoy',
            'totalVentasMes',
            'cantidadVentasMes',
            'gastosHoy',
            'gastosMes',
            'totalProductos',
            'productosStockBajo',
            'productosSinStock',
            'valorInventario',
            'totalClientes',
            'clientesNuevosMes',
            'ventasPorDia',
            'productosTop',
            'ventasRecientes',
            'alertasStock',
            'configuracion'
        ));
    }
}
