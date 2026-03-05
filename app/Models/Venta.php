<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'numero_venta',
        'user_id',
        'cliente_id',
        'subtotal',
        'descuento',
        'impuesto',
        'total',
        'metodo_pago',
        'efectivo_recibido',
        'cambio',
        'estado',
        'notas',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'total' => 'decimal:2',
        'efectivo_recibido' => 'decimal:2',
        'cambio' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($venta) {
            if (empty($venta->numero_venta)) {
                $venta->numero_venta = 'V-' . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'detalle_ventas', 'venta_id', 'producto_id')
                    ->withPivot('cantidad', 'precio_unitario', 'descuento', 'subtotal');
    }

    public function scopePagadas($query)
    {
        return $query->where('estado', 'pagada');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAnuladas($query)
    {
        return $query->where('estado', 'anulada');
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('created_at', [$desde, $hasta]);
    }

    public function getCantidadProductosAttribute()
    {
        return $this->detalles->sum('cantidad');
    }

    public function getGananciaAttribute()
    {
        return $this->detalles->sum(function ($detalle) {
            return ($detalle->precio_unitario - $detalle->producto->precio_compra) * $detalle->cantidad;
        });
    }

    public function anular()
    {
        if ($this->estado !== 'anulada') {
            foreach ($this->detalles as $detalle) {
                $detalle->producto->aumentarStock($detalle->cantidad);
            }
            $this->estado = 'anulada';
            $this->save();
            return true;
        }
        return false;
    }

    public function calcularTotales()
    {
        $this->subtotal = $this->detalles->sum('subtotal');
        $this->impuesto = $this->subtotal * (config('app.impuesto', 0.19));
        $this->total = $this->subtotal - $this->descuento + $this->impuesto;
        $this->save();
    }
}
