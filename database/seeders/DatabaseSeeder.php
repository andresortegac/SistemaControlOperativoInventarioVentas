<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Configuracion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@licoreras.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'activo' => true,
        ]);

        // Crear usuario cajero
        User::create([
            'name' => 'Cajero',
            'email' => 'cajero@licoreras.com',
            'password' => Hash::make('password'),
            'role' => 'cajero',
            'activo' => true,
        ]);

        // Crear configuración por defecto
        Configuracion::create([
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

        // Crear categorías por defecto
        $categorias = [
            ['nombre' => 'Cervezas', 'color' => '#FDB813'],
            ['nombre' => 'Vinos', 'color' => '#722F37'],
            ['nombre' => 'Whisky', 'color' => '#D4A574'],
            ['nombre' => 'Ron', 'color' => '#8B4513'],
            ['nombre' => 'Vodka', 'color' => '#E0E0E0'],
            ['nombre' => 'Tequila', 'color' => '#F4E4C1'],
            ['nombre' => 'Aguardiente', 'color' => '#4A90A4'],
            ['nombre' => 'Snacks', 'color' => '#FF6B6B'],
            ['nombre' => 'Bebidas No Alcohólicas', 'color' => '#4ECDC4'],
            ['nombre' => 'Otros', 'color' => '#95A5A6'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create([
                'nombre' => $categoria['nombre'],
                'slug' => \Illuminate\Support\Str::slug($categoria['nombre']),
                'color' => $categoria['color'],
                'activo' => true,
            ]);
        }
    }
}
