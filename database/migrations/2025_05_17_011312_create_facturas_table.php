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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('orden_id')->nullable()->constrained('ordenes');
            $table->foreignId('metodo_pago_id')->nullable()->constrained('metodos_pagos');
            $table->foreignId('igv_id')->nullable()->constrained('igv');
            $table->string('nro_factura')->unique()->nullable();
            $table->decimal('monto_total', 10, 2)->nullable();
            $table->decimal('monto_total_igv', 10, 2)->nullable();
            $table->string('estado_pago')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->date('hora_pago')->nullable();
            $table->string('notas')->nullable();
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
        Schema::dropIfExists('facturas');
    }
};
