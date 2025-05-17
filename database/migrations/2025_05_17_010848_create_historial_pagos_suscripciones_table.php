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
        Schema::create('historial_pagos_suscripciones', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('tenant_suscripcion_id')->constrained('tenants_suscripciones');
            $table->decimal('monto', 10, 2)->nullable();
            $table->date('fecha_pago')->nullable();
            $table->string('estado')->nullable();
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
        Schema::dropIfExists('historial_pagos_suscripciones');
    }
};
