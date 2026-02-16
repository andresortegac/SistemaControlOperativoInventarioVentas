<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuracion';

    protected $fillable = [
        'nombre_negocio',
        'slogan',
        'nit',
        'direccion',
        'telefono',
        'email',
        'logo',
        'impuesto_porcentaje',
        'moneda',
        'simbolo_moneda',
        'decimales',
        'formato_fecha',
        'factura_con_impuesto',
        'mensaje_factura',
        'stock_alerta',
    ];

    protected $casts = [
        'impuesto_porcentaje' => 'decimal:2',
        'decimales' => 'integer',
        'stock_alerta' => 'integer',
        'factura_con_impuesto' => 'boolean',
    ];

    private static $configuracionCache = null;

    public static function getConfiguracion()
    {
        if (self::$configuracionCache === null) {
            self::$configuracionCache = self::first() ?? self::crearDefault();
        }
        return self::$configuracionCache;
    }

    public static function crearDefault()
    {
        return self::create([
            'nombre_negocio' => 'Licoreras',
            'slogan' => 'Sistema de Control de Inventario',
            'impuesto_porcentaje' => 19.00,
            'moneda' => 'COP',
            'simbolo_moneda' => '$',
            'decimales' => 0,
            'formato_fecha' => 'd/m/Y',
            'factura_con_impuesto' => true,
            'stock_alerta' => 5,
        ]);
    }

    public static function getImpuesto()
    {
        return self::getConfiguracion()->impuesto_porcentaje / 100;
    }

    public static function formatearDinero($monto)
    {
        $config = self::getConfiguracion();
        return $config->simbolo_moneda . ' ' . number_format($monto, $config->decimales, ',', '.');
    }

    public static function formatearFecha($fecha)
    {
        $config = self::getConfiguracion();
        return $fecha->format($config->formato_fecha);
    }
}
