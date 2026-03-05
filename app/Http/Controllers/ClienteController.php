<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('documento', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%");
            });
        }

        $clientes = $query->orderBy('nombre')->paginate(20);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'nullable|string|max:50|unique:clientes',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'notas' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre del cliente es obligatorio',
            'documento.unique' => 'Este documento ya está registrado',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load(['ventas' => function($query) {
            $query->orderByDesc('created_at')->limit(10);
        }]);
        
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'nullable|string|max:50|unique:clientes,documento,' . $cliente->id,
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'notas' => 'nullable|string',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->ventas()->count() > 0) {
            $cliente->update(['activo' => false]);
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente desactivado exitosamente');
        }

        $cliente->delete();
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }

    public function activar(Cliente $cliente)
    {
        $cliente->update(['activo' => true]);
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente activado exitosamente');
    }

    public function buscarAjax(Request $request)
    {
        $termino = $request->input('q');
        
        $clientes = Cliente::where('activo', true)
            ->where(function($query) use ($termino) {
                $query->where('nombre', 'like', "%{$termino}%")
                      ->orWhere('documento', 'like', "%{$termino}%");
            })
            ->limit(10)
            ->get(['id', 'nombre', 'documento', 'telefono']);

        return $this->success('Clientes encontrados', $clientes);
    }
}
