<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoController extends Controller
{
    public function index(Request $request)
    {
        $query = Gasto::with('user');

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('fecha_gasto', [$request->desde, $request->hasta]);
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('concepto', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%")
                  ->orWhere('numero_comprobante', 'like', "%{$buscar}%");
            });
        }

        $gastos = $query->orderByDesc('fecha_gasto')->paginate(20);
        $totalGastos = $gastos->sum('monto');
        $categorias = Gasto::getCategorias();

        return view('gastos.index', compact('gastos', 'totalGastos', 'categorias'));
    }

    public function create()
    {
        $categorias = Gasto::getCategorias();
        return view('gastos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'concepto' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|in:servicios,nomina,proveedores,mantenimiento,impuestos,otros',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|max:50',
            'numero_comprobante' => 'nullable|string|max:50',
            'fecha_gasto' => 'required|date',
            'notas' => 'nullable|string',
        ], [
            'concepto.required' => 'El concepto del gasto es obligatorio',
            'categoria.required' => 'La categoría es obligatoria',
            'monto.required' => 'El monto es obligatorio',
            'monto.min' => 'El monto debe ser mayor a 0',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        Gasto::create($data);

        return redirect()->route('gastos.index')
            ->with('success', 'Gasto registrado exitosamente');
    }

    public function show(Gasto $gasto)
    {
        return view('gastos.show', compact('gasto'));
    }

    public function edit(Gasto $gasto)
    {
        $categorias = Gasto::getCategorias();
        return view('gastos.edit', compact('gasto', 'categorias'));
    }

    public function update(Request $request, Gasto $gasto)
    {
        $request->validate([
            'concepto' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|in:servicios,nomina,proveedores,mantenimiento,impuestos,otros',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|max:50',
            'numero_comprobante' => 'nullable|string|max:50',
            'fecha_gasto' => 'required|date',
            'notas' => 'nullable|string',
        ]);

        $gasto->update($request->all());

        return redirect()->route('gastos.index')
            ->with('success', 'Gasto actualizado exitosamente');
    }

    public function destroy(Gasto $gasto)
    {
        $gasto->delete();
        return redirect()->route('gastos.index')
            ->with('success', 'Gasto eliminado exitosamente');
    }

    public function estadisticas(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->format('Y-m-d');
        $hasta = $request->hasta ?? now()->format('Y-m-d');

        $gastos = Gasto::whereBetween('fecha_gasto', [$desde, $hasta])->get();

        $totalGastos = $gastos->sum('monto');
        $cantidadGastos = $gastos->count();
        $promedioGasto = $cantidadGastos > 0 ? $totalGastos / $cantidadGastos : 0;

        $gastosPorCategoria = $gastos->groupBy('categoria')->map(function($group) {
            return [
                'cantidad' => $group->count(),
                'total' => $group->sum('monto'),
            ];
        });

        $gastosPorDia = $gastos->groupBy(function($gasto) {
            return $gasto->fecha_gasto->format('Y-m-d');
        })->map(function($group) {
            return [
                'cantidad' => $group->count(),
                'total' => $group->sum('monto'),
            ];
        });

        $categorias = Gasto::getCategorias();

        return view('gastos.estadisticas', compact(
            'totalGastos',
            'cantidadGastos',
            'promedioGasto',
            'gastosPorCategoria',
            'gastosPorDia',
            'categorias',
            'desde',
            'hasta'
        ));
    }
}
