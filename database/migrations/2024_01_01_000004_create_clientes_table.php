<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('documento')->nullable()->unique();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('direccion')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->decimal('puntos_fidelidad', 10, 2)->default(0);
            $table->text('notas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
