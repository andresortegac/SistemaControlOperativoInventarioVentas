<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'proveedor']);

        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'activo') {
                $query->where('activo', true);
            } elseif ($request->estado === 'inactivo') {
                $query->where('activo', false);
            } elseif ($request->estado === 'stock_bajo') {
                $query->stockBajo();
            }
        }

        $productos = $query->orderBy('nombre')->paginate(20);
        $categorias = Categoria::where('activo', true)->get();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();
        return view('productos.create', compact('categorias', 'proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:productos',
            'codigo_barras' => 'nullable|string|max:50|unique:productos',
            'categoria_id' => 'required|exists:categorias,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'unidad_medida' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|max:2048',
            'fecha_vencimiento' => 'nullable|date',
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio',
            'codigo.required' => 'El código del producto es obligatorio',
            'codigo.unique' => 'Este código ya está en uso',
            'codigo_barras.unique' => 'Este código de barras ya está en uso',
            'categoria_id.required' => 'La categoría es obligatoria',
            'precio_compra.required' => 'El precio de compra es obligatorio',
            'precio_venta.required' => 'El precio de venta es obligatorio',
        ]);

        $data = $request->except('imagen');

        if (empty($data['codigo'])) {
            $data['codigo'] = 'PROD-' . strtoupper(Str::random(8));
        }

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . Str::slug($request->nombre) . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images/productos'), $nombreImagen);
            $data['imagen'] = 'images/productos/' . $nombreImagen;
        }

        $producto = Producto::create($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show(Producto $producto)
    {
        $producto->load(['categoria', 'proveedor', 'detalleVentas.venta', 'entradasInventario', 'salidasInventario']);
        
        // Generar código de barras
        $generator = new BarcodeGeneratorHTML();
        $barcode = null;
        if ($producto->codigo_barras) {
            $barcode = $generator->getBarcode($producto->codigo_barras, $generator::TYPE_CODE_128);
        }

        return view('productos.show', compact('producto', 'barcode'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();
        return view('productos.edit', compact('producto', 'categorias', 'proveedores'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:productos,codigo,' . $producto->id,
            'codigo_barras' => 'nullable|string|max:50|unique:productos,codigo_barras,' . $producto->id,
            'categoria_id' => 'required|exists:categorias,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'unidad_medida' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|max:2048',
            'fecha_vencimiento' => 'nullable|date',
            'activo' => 'boolean',
        ]);

        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            if ($producto->imagen && file_exists(public_path($producto->imagen))) {
                unlink(public_path($producto->imagen));
            }
            
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . Str::slug($request->nombre) . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images/productos'), $nombreImagen);
            $data['imagen'] = 'images/productos/' . $nombreImagen;
        }

        $producto->update($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Producto $producto)
    {
        try {
            $producto->update(['activo' => false]);
            return redirect()->route('productos.index')
                ->with('success', 'Producto desactivado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede desactivar el producto');
        }
    }

    public function activar(Producto $producto)
    {
        $producto->update(['activo' => true]);
        return redirect()->route('productos.index')
            ->with('success', 'Producto activado exitosamente');
    }

    public function buscarPorCodigo(Request $request)
    {
        $codigo = $request->input('codigo');
        
        $producto = Producto::where(function($query) use ($codigo) {
                $query->where('codigo', $codigo)
                      ->orWhere('codigo_barras', $codigo);
            })
            ->where('activo', true)
            ->first();

        if ($producto) {
            return $this->success('Producto encontrado', $producto);
        }

        return $this->error('Producto no encontrado', 404);
    }

    public function buscarAjax(Request $request)
    {
        $termino = $request->input('q');
        
        $productos = Producto::where('activo', true)
            ->where(function($query) use ($termino) {
                $query->where('nombre', 'like', "%{$termino}%")
                      ->orWhere('codigo', 'like', "%{$termino}%")
                      ->orWhere('codigo_barras', 'like', "%{$termino}%");
            })
            ->limit(10)
            ->get(['id', 'nombre', 'codigo', 'codigo_barras', 'precio_venta', 'stock']);

        return $this->success('Productos encontrados', $productos);
    }

    public function generarCodigoBarras(Producto $producto)
    {
        if (!$producto->codigo_barras) {
            return redirect()->back()->with('error', 'El producto no tiene código de barras');
        }

        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($producto->codigo_barras, $generator::TYPE_CODE_128);
        
        $filename = 'barcode_' . $producto->codigo_barras . '.png';
        $path = public_path('barcodes/' . $filename);
        
        if (!file_exists(public_path('barcodes'))) {
            mkdir(public_path('barcodes'), 0755, true);
        }
        
        file_put_contents($path, $barcode);

        return response()->download($path, $filename);
    }

    public function imprimirCodigos(Request $request)
    {
        $productos = Producto::whereIn('id', $request->productos ?? [])
            ->whereNotNull('codigo_barras')
            ->get();

        $generator = new BarcodeGeneratorHTML();
        
        return view('productos.imprimir-codigos', compact('productos', 'generator'));
    }

    public function stockBajo()
    {
        $productos = Producto::with(['categoria', 'proveedor'])
            ->where('activo', true)
            ->stockBajo()
            ->orderBy('stock')
            ->paginate(20);

        return view('productos.stock-bajo', compact('productos'));
    }

    public function inventario()
    {
        $productos = Producto::with(['categoria'])
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $totalValor = $productos->sum(function($p) {
            return $p->stock * $p->precio_compra;
        });

        return view('productos.inventario', compact('productos', 'totalValor'));
    }
}
