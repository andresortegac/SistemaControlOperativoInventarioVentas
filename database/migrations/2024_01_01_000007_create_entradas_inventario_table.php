<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entradas_inventario', function (Blueprint $table) {
            $table->id();
            $table->string('numero_entrada')->unique();
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('cantidad');
            $table->decimal('costo_unitario', 12, 2)->nullable();
            $table->decimal('costo_total', 12, 2)->nullable();
            $table->string('numero_factura')->nullable();
            $table->text('motivo')->nullable();
            $table->date('fecha_entrada');
            $table->text('notas')->nullable();
            $table->timestamps();
            
            $table->index('numero_entrada');
            $table->index('fecha_entrada');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entradas_inventario');
    }
};
