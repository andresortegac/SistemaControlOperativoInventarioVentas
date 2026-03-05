<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('productos')->orderBy('nombre')->paginate(20);
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias',
            'descripcion' => 'nullable|string',
            'color' => 'required|string|max:7',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio',
            'nombre.unique' => 'Esta categoría ya existe',
        ]);

        Categoria::create([
            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre),
            'descripcion' => $request->descripcion,
            'color' => $request->color,
        ]);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada exitosamente');
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string',
            'color' => 'required|string|max:7',
        ]);

        $categoria->update([
            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre),
            'descripcion' => $request->descripcion,
            'color' => $request->color,
        ]);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada exitosamente');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->count() > 0) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene productos asociados');
        }

        $categoria->delete();
        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }
}
