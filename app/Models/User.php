<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telefono',
        'direccion',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function entradasInventario()
    {
        return $this->hasMany(EntradaInventario::class);
    }

    public function salidasInventario()
    {
        return $this->hasMany(SalidaInventario::class);
    }

    public function gastos()
    {
        return $this->hasMany(Gasto::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCajero()
    {
        return $this->role === 'cajero';
    }

    public function isAlmacen()
    {
        return $this->role === 'almacen';
    }
}
