<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $table = 'gastos';

    protected $fillable = [
        'numero_gasto',
        'user_id',
        'concepto',
        'descripcion',
        'categoria',
        'monto',
        'metodo_pago',
        'numero_comprobante',
        'fecha_gasto',
        'notas',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_gasto' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($gasto) {
            if (empty($gasto->numero_gasto)) {
                $gasto->numero_gasto = 'G-' . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('fecha_gasto', [$desde, $hasta]);
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function getCategoriaTextoAttribute()
    {
        $categorias = [
            'servicios' => 'Servicios Públicos',
            'nomina' => 'Nómina',
            'proveedores' => 'Proveedores',
            'mantenimiento' => 'Mantenimiento',
            'impuestos' => 'Impuestos',
            'otros' => 'Otros',
        ];
        return $categorias[$this->categoria] ?? 'Otros';
    }

    public static function getCategorias()
    {
        return [
            'servicios' => 'Servicios Públicos',
            'nomina' => 'Nómina',
            'proveedores' => 'Proveedores',
            'mantenimiento' => 'Mantenimiento',
            'impuestos' => 'Impuestos',
            'otros' => 'Otros',
        ];
    }
}
