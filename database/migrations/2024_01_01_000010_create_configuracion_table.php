<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_negocio')->default('Licoreras');
            $table->string('slogan')->nullable();
            $table->string('nit')->nullable();
            $table->text('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->decimal('impuesto_porcentaje', 5, 2)->default(19.00);
            $table->string('moneda')->default('COP');
            $table->string('simbolo_moneda')->default('$');
            $table->integer('decimales')->default(0);
            $table->string('formato_fecha')->default('d/m/Y');
            $table->boolean('factura_con_impuesto')->default(true);
            $table->text('mensaje_factura')->nullable();
            $table->integer('stock_alerta')->default(5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion');
    }
};
