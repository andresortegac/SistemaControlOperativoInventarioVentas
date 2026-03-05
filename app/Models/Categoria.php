<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'color',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($categoria) {
            if (empty($categoria->slug)) {
                $categoria->slug = Str::slug($categoria->nombre);
            }
        });
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function getProductosCountAttribute()
    {
        return $this->productos()->count();
    }
}
