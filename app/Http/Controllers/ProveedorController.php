<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::withCount('productos')->orderBy('nombre')->paginate(20);
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nombre_contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'nit' => 'nullable|string|max:50',
            'notas' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre del proveedor es obligatorio',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente');
    }

    public function show(Proveedor $proveedor)
    {
        $proveedor->load(['productos', 'entradasInventario']);
        return view('proveedores.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nombre_contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'nit' => 'nullable|string|max:50',
            'notas' => 'nullable|string',
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente');
    }

    public function destroy(Proveedor $proveedor)
    {
        if ($proveedor->productos()->count() > 0) {
            $proveedor->update(['activo' => false]);
            return redirect()->route('proveedores.index')
                ->with('success', 'Proveedor desactivado exitosamente');
        }

        $proveedor->delete();
        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente');
    }

    public function activar(Proveedor $proveedor)
    {
        $proveedor->update(['activo' => true]);
        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor activado exitosamente');
    }
}
