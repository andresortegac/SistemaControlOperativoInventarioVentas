<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaInventario extends Model
{
    use HasFactory;

    protected $table = 'entradas_inventario';

    protected $fillable = [
        'numero_entrada',
        'producto_id',
        'proveedor_id',
        'user_id',
        'cantidad',
        'costo_unitario',
        'costo_total',
        'numero_factura',
        'motivo',
        'fecha_entrada',
        'notas',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'costo_unitario' => 'decimal:2',
        'costo_total' => 'decimal:2',
        'fecha_entrada' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($entrada) {
            if (empty($entrada->numero_entrada)) {
                $entrada->numero_entrada = 'E-' . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
            if (empty($entrada->costo_total) && $entrada->costo_unitario) {
                $entrada->costo_total = $entrada->costo_unitario * $entrada->cantidad;
            }
        });

        static::created(function ($entrada) {
            $entrada->producto->aumentarStock($entrada->cantidad);
        });
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('fecha_entrada', [$desde, $hasta]);
    }
}
