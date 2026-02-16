<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::with(['user', 'cliente']);

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('created_at', [$request->desde . ' 00:00:00', $request->hasta . ' 23:59:59']);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('numero_venta', 'like', "%{$buscar}%")
                  ->orWhereHas('cliente', function($q) use ($buscar) {
                      $q->where('nombre', 'like', "%{$buscar}%");
                  });
            });
        }

        $ventas = $query->orderByDesc('created_at')->paginate(20);
        
        $totalVentas = $ventas->sum('total');
        $totalDescuentos = $ventas->sum('descuento');

        return view('ventas.index', compact('ventas', 'totalVentas', 'totalDescuentos'));
    }

    public function create()
    {
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        $configuracion = Configuracion::getConfiguracion();
        
        return view('ventas.create', compact('clientes', 'configuracion'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,mixto',
            'efectivo_recibido' => 'nullable|numeric|min:0',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $productosData = [];

            foreach ($request->productos as $prod) {
                $producto = Producto::find($prod['id']);
                
                if ($producto->stock < $prod['cantidad']) {
                    DB::rollBack();
                    return $this->error("Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock}");
                }

                $itemSubtotal = $prod['cantidad'] * $prod['precio'];
                $subtotal += $itemSubtotal;

                $productosData[] = [
                    'producto' => $producto,
                    'cantidad' => $prod['cantidad'],
                    'precio' => $prod['precio'],
                    'subtotal' => $itemSubtotal,
                ];
            }

            $configuracion = Configuracion::getConfiguracion();
            $descuento = $request->descuento ?? 0;
            $impuesto = $subtotal * ($configuracion->impuesto_porcentaje / 100);
            $total = $subtotal - $descuento + $impuesto;

            $cambio = 0;
            if ($request->metodo_pago === 'efectivo' && $request->efectivo_recibido) {
                $cambio = $request->efectivo_recibido - $total;
            }

            $venta = Venta::create([
                'user_id' => Auth::id(),
                'cliente_id' => $request->cliente_id,
                'subtotal' => $subtotal,
                'descuento' => $descuento,
                'impuesto' => $impuesto,
                'total' => $total,
                'metodo_pago' => $request->metodo_pago,
                'efectivo_recibido' => $request->efectivo_recibido,
                'cambio' => $cambio,
                'estado' => 'pagada',
                'notas' => $request->notas,
            ]);

            foreach ($productosData as $data) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $data['producto']->id,
                    'cantidad' => $data['cantidad'],
                    'precio_unitario' => $data['precio'],
                    'subtotal' => $data['subtotal'],
                ]);

                $data['producto']->disminuirStock($data['cantidad']);
            }

            if ($request->cliente_id) {
                $cliente = Cliente::find($request->cliente_id);
                $cliente->agregarPuntos($total * 0.01);
            }

            DB::commit();

            return $this->success('Venta realizada exitosamente', [
                'venta_id' => $venta->id,
                'numero_venta' => $venta->numero_venta,
                'total' => $total,
                'cambio' => $cambio,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error al procesar la venta: ' . $e->getMessage());
        }
    }

    public function show(Venta $venta)
    {
        $venta->load(['user', 'cliente', 'detalles.producto']);
        $configuracion = Configuracion::getConfiguracion();
        
        return view('ventas.show', compact('venta', 'configuracion'));
    }

    public function ticket(Venta $venta)
    {
        $venta->load(['user', 'cliente', 'detalles.producto']);
        $configuracion = Configuracion::getConfiguracion();
        
        return view('ventas.ticket', compact('venta', 'configuracion'));
    }

    public function pdf(Venta $venta)
    {
        $venta->load(['user', 'cliente', 'detalles.producto']);
        $configuracion = Configuracion::getConfiguracion();
        
        $pdf = PDF::loadView('ventas.pdf', compact('venta', 'configuracion'));
        
        return $pdf->download('venta-' . $venta->numero_venta . '.pdf');
    }

    public function anular(Venta $venta)
    {
        if ($venta->estado === 'anulada') {
            return redirect()->back()->with('error', 'La venta ya está anulada');
        }

        DB::beginTransaction();

        try {
            $venta->anular();
            DB::commit();
            
            return redirect()->back()->with('success', 'Venta anulada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al anular la venta');
        }
    }

    public function puntoVenta()
    {
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        $configuracion = Configuracion::getConfiguracion();
        
        return view('ventas.punto-venta', compact('clientes', 'configuracion'));
    }

    public function agregarProducto(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
        ]);

        $producto = Producto::where(function($query) use ($request) {
                $query->where('codigo', $request->codigo)
                      ->orWhere('codigo_barras', $request->codigo);
            })
            ->where('activo', true)
            ->first();

        if (!$producto) {
            return $this->error('Producto no encontrado', 404);
        }

        if ($producto->stock <= 0) {
            return $this->error('Producto sin stock disponible', 400);
        }

        return $this->success('Producto agregado', [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'codigo' => $producto->codigo,
            'codigo_barras' => $producto->codigo_barras,
            'precio_venta' => $producto->precio_venta,
            'stock' => $producto->stock,
            'imagen' => $producto->imagen,
        ]);
    }

    public function estadisticas(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->format('Y-m-d');
        $hasta = $request->hasta ?? now()->format('Y-m-d');

        $ventas = Venta::whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->where('estado', 'pagada')
            ->get();

        $totalVentas = $ventas->sum('total');
        $cantidadVentas = $ventas->count();
        $promedioVenta = $cantidadVentas > 0 ? $totalVentas / $cantidadVentas : 0;

        $ventasPorMetodo = $ventas->groupBy('metodo_pago')->map(function($group) {
            return [
                'cantidad' => $group->count(),
                'total' => $group->sum('total'),
            ];
        });

        $ventasPorDia = $ventas->groupBy(function($venta) {
            return $venta->created_at->format('Y-m-d');
        })->map(function($group) {
            return [
                'cantidad' => $group->count(),
                'total' => $group->sum('total'),
            ];
        });

        return view('ventas.estadisticas', compact(
            'totalVentas',
            'cantidadVentas',
            'promedioVenta',
            'ventasPorMetodo',
            'ventasPorDia',
            'desde',
            'hasta'
        ));
    }
}
