<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaInventario extends Model
{
    use HasFactory;

    protected $table = 'salidas_inventario';

    protected $fillable = [
        'numero_salida',
        'producto_id',
        'user_id',
        'cantidad',
        'motivo',
        'descripcion',
        'fecha_salida',
        'notas',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'fecha_salida' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($salida) {
            if (empty($salida->numero_salida)) {
                $salida->numero_salida = 'S-' . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($salida) {
            $salida->producto->disminuirStock($salida->cantidad);
        });
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('fecha_salida', [$desde, $hasta]);
    }

    public function scopePorMotivo($query, $motivo)
    {
        return $query->where('motivo', $motivo);
    }

    public function getMotivoTextoAttribute()
    {
        $motivos = [
            'venta' => 'Venta',
            'merma' => 'Merma',
            'devolucion' => 'Devolución',
            'caducado' => 'Producto Caducado',
            'otro' => 'Otro',
        ];
        return $motivos[$this->motivo] ?? 'Otro';
    }
}
