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
        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained('cajas');
            $table->foreignId('factura_id')->nullable()->constrained('facturas');
            $table->foreignId('tipo_movimiento_caja_id')->constrained('tipos_movimiento_caja');
            $table->foreignId('metodo_pago_id')->constrained('metodos_pagos');
            $table->decimal('monto', 10, 2);
            $table->string('descripcion')->nullable();
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (app()->environment('production')) {
            throw new Exception('The "down" method is disabled in production.');
        }
        Schema::dropIfExists('movimientos_caja');
    }
}; 