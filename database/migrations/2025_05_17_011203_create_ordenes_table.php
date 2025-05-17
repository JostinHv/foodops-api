<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('restaurante_id')->constrained('restaurantes');
            $table->foreignId('sucursal_id')->constrained('sucursales');
            $table->foreignId('mesa_id')->constrained('mesas');
            $table->foreignId('estado_orden_id')->constrained('estados_ordenes');
            $table->foreignId('mesero_id')->constrained('usuarios');
            $table->foreignId('cajero_id')->constrained('usuarios');
            $table->string('nro_orden')->unique()->nullable();
            $table->string('nombre_cliente')->nullable();
            $table->string('peticiones_especiales')->nullable();
            $table->string('tipo_servicio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
