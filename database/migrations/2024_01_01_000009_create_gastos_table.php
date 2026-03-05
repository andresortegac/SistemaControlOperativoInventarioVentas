<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_gasto')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->string('concepto');
            $table->text('descripcion')->nullable();
            $table->enum('categoria', ['servicios', 'nomina', 'proveedores', 'mantenimiento', 'impuestos', 'otros'])->default('otros');
            $table->decimal('monto', 12, 2);
            $table->string('metodo_pago')->default('efectivo');
            $table->string('numero_comprobante')->nullable();
            $table->date('fecha_gasto');
            $table->text('notas')->nullable();
            $table->timestamps();
            
            $table->index('numero_gasto');
            $table->index('fecha_gasto');
            $table->index('categoria');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
