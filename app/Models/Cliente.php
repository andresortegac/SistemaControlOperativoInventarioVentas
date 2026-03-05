<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'documento',
        'telefono',
        'email',
        'direccion',
        'fecha_nacimiento',
        'puntos_fidelidad',
        'notas',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'puntos_fidelidad' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function getTotalComprasAttribute()
    {
        return $this->ventas()->where('estado', 'pagada')->sum('total');
    }

    public function getCantidadComprasAttribute()
    {
        return $this->ventas()->where('estado', 'pagada')->count();
    }

    public function agregarPuntos($puntos)
    {
        $this->puntos_fidelidad += $puntos;
        $this->save();
    }
}
