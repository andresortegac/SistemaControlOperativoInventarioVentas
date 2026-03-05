<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'codigo_barras',
        'nombre',
        'descripcion',
        'categoria_id',
        'proveedor_id',
        'precio_compra',
        'precio_venta',
        'stock',
        'stock_minimo',
        'unidad_medida',
        'imagen',
        'fecha_vencimiento',
        'activo',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock' => 'integer',
        'stock_minimo' => 'integer',
        'fecha_vencimiento' => 'date',
        'activo' => 'boolean',
    ];

    protected $appends = ['ganancia', 'stock_bajo'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function entradasInventario()
    {
        return $this->hasMany(EntradaInventario::class);
    }

    public function salidasInventario()
    {
        return $this->hasMany(SalidaInventario::class);
    }

    public function getGananciaAttribute()
    {
        return $this->precio_venta - $this->precio_compra;
    }

    public function getGananciaPorcentajeAttribute()
    {
        if ($this->precio_compra <= 0) return 0;
        return (($this->precio_venta - $this->precio_compra) / $this->precio_compra) * 100;
    }

    public function getStockBajoAttribute()
    {
        return $this->stock <= $this->stock_minimo;
    }

    public function getValorInventarioAttribute()
    {
        return $this->stock * $this->precio_compra;
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeStockBajo($query)
    {
        return $query->whereColumn('stock', '<=', 'stock_minimo');
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('codigo', 'like', "%{$termino}%")
              ->orWhere('codigo_barras', 'like', "%{$termino}%");
        });
    }

    public function aumentarStock($cantidad)
    {
        $this->stock += $cantidad;
        $this->save();
    }

    public function disminuirStock($cantidad)
    {
        if ($this->stock >= $cantidad) {
            $this->stock -= $cantidad;
            $this->save();
            return true;
        }
        return false;
    }
}
