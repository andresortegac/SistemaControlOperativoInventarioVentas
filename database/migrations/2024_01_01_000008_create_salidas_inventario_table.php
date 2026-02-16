<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salidas_inventario', function (Blueprint $table) {
            $table->id();
            $table->string('numero_salida')->unique();
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('cantidad');
            $table->enum('motivo', ['venta', 'merma', 'devolucion', 'caducado', 'otro'])->default('otro');
            $table->text('descripcion')->nullable();
            $table->date('fecha_salida');
            $table->text('notas')->nullable();
            $table->timestamps();
            
            $table->index('numero_salida');
            $table->index('fecha_salida');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salidas_inventario');
    }
};
