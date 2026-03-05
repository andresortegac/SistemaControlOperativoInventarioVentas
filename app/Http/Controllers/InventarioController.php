<?php

namespace App\Http\Controllers;

use App\Models\EntradaInventario;
use App\Models\SalidaInventario;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    // ========== ENTRADAS ==========
    
    public function entradas(Request $request)
    {
        $query = EntradaInventario::with(['producto', 'proveedor', 'user']);

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('fecha_entrada', [$request->desde, $request->hasta]);
        }

        if ($request->filled('producto')) {
            $query->where('producto_id', $request->producto);
        }

        $entradas = $query->orderByDesc('fecha_entrada')->paginate(20);
        $productos = Producto::where('activo', true)->orderBy('nombre')->get();

        return view('inventario.entradas', compact('entradas', 'productos'));
    }

    public function createEntrada()
    {
        $productos = Producto::where('activo', true)->orderBy('nombre')->get();
        $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
        return view('inventario.create-entrada', compact('productos', 'proveedores'));
    }

    public function storeEntrada(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'cantidad' => 'required|integer|min:1',
            'costo_unitario' => 'nullable|numeric|min:0',
            'numero_factura' => 'nullable|string|max:50',
            'motivo' => 'nullable|string',
            'fecha_entrada' => 'required|date',
            'notas' => 'nullable|string',
        ], [
            'producto_id.required' => 'El producto es obligatorio',
            'cantidad.required' => 'La cantidad es obligatoria',
            'cantidad.min' => 'La cantidad debe ser mayor a 0',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        EntradaInventario::create($data);

        return redirect()->route('inventario.entradas')
            ->with('success', 'Entrada de inventario registrada exitosamente');
    }

    // ========== SALIDAS ==========
    
    public function salidas(Request $request)
    {
        $query = SalidaInventario::with(['producto', 'user']);

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('fecha_salida', [$request->desde, $request->hasta]);
        }

        if ($request->filled('motivo')) {
            $query->where('motivo', $request->motivo);
        }

        $salidas = $query->orderByDesc('fecha_salida')->paginate(20);

        return view('inventario.salidas', compact('salidas'));
    }

    public function createSalida()
    {
        $productos = Producto::where('activo', true)->where('stock', '>', 0)->orderBy('nombre')->get();
        return view('inventario.create-salida', compact('productos'));
    }

    public function storeSalida(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|in:merma,devolucion,caducado,otro',
            'descripcion' => 'nullable|string',
            'fecha_salida' => 'required|date',
            'notas' => 'nullable|string',
        ], [
            'producto_id.required' => 'El producto es obligatorio',
            'cantidad.required' => 'La cantidad es obligatoria',
            'motivo.required' => 'El motivo es obligatorio',
        ]);

        $producto = Producto::find($request->producto_id);

        if ($producto->stock < $request->cantidad) {
            return redirect()->back()
                ->with('error', 'Stock insuficiente. Disponible: ' . $producto->stock)
                ->withInput();
        }

        $data = $request->all();
        $data['user_id'] = Auth::id();

        SalidaInventario::create($data);

        return redirect()->route('inventario.salidas')
            ->with('success', 'Salida de inventario registrada exitosamente');
    }

    // ========== MOVIMIENTOS ==========
    
    public function movimientos(Request $request)
    {
        $producto_id = $request->producto_id;
        $producto = null;
        $entradas = collect();
        $salidas = collect();

        if ($producto_id) {
            $producto = Producto::find($producto_id);
            
            $entradas = EntradaInventario::where('producto_id', $producto_id)
                ->orderByDesc('fecha_entrada')
                ->get();
            
            $salidas = SalidaInventario::where('producto_id', $producto_id)
                ->orderByDesc('fecha_salida')
                ->get();
        }

        $productos = Producto::where('activo', true)->orderBy('nombre')->get();

        return view('inventario.movimientos', compact('productos', 'producto', 'entradas', 'salidas'));
    }

    public function kardex(Producto $producto)
    {
        $entradas = EntradaInventario::where('producto_id', $producto->id)
            ->orderBy('fecha_entrada')
            ->get()
            ->map(function($e) {
                return [
                    'fecha' => $e->fecha_entrada,
                    'tipo' => 'entrada',
                    'documento' => $e->numero_entrada,
                    'cantidad' => $e->cantidad,
                    'costo' => $e->costo_unitario,
                ];
            });

        $salidas = SalidaInventario::where('producto_id', $producto->id)
            ->orderBy('fecha_salida')
            ->get()
            ->map(function($s) {
                return [
                    'fecha' => $s->fecha_salida,
                    'tipo' => 'salida',
                    'documento' => $s->numero_salida,
                    'cantidad' => $s->cantidad,
                    'motivo' => $s->motivo,
                ];
            });

        $movimientos = $entradas->merge($salidas)->sortBy('fecha');

        $saldo = 0;
        $movimientos = $movimientos->map(function($m) use (&$saldo) {
            if ($m['tipo'] === 'entrada') {
                $saldo += $m['cantidad'];
            } else {
                $saldo -= $m['cantidad'];
            }
            $m['saldo'] = $saldo;
            return $m;
        });

        return view('inventario.kardex', compact('producto', 'movimientos'));
    }

    public function ajuste(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad_actual' => 'required|integer|min:0',
            'motivo' => 'required|string',
        ]);

        $producto = Producto::find($request->producto_id);
        $diferencia = $request->cantidad_actual - $producto->stock;

        DB::beginTransaction();

        try {
            if ($diferencia > 0) {
                EntradaInventario::create([
                    'producto_id' => $producto->id,
                    'user_id' => Auth::id(),
                    'cantidad' => $diferencia,
                    'motivo' => 'Ajuste de inventario: ' . $request->motivo,
                    'fecha_entrada' => now(),
                ]);
            } elseif ($diferencia < 0) {
                SalidaInventario::create([
                    'producto_id' => $producto->id,
                    'user_id' => Auth::id(),
                    'cantidad' => abs($diferencia),
                    'motivo' => 'otro',
                    'descripcion' => 'Ajuste de inventario: ' . $request->motivo,
                    'fecha_salida' => now(),
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Ajuste de inventario realizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al realizar el ajuste: ' . $e->getMessage());
        }
    }
}
