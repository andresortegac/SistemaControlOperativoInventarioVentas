<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_ventas';

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento',
        'subtotal',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'descuento' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function getGananciaAttribute()
    {
        return ($this->precio_unitario - $this->producto->precio_compra) * $this->cantidad;
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($detalle) {
            $detalle->subtotal = ($detalle->precio_unitario * $detalle->cantidad) - $detalle->descuento;
        });
    }
}
