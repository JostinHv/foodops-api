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
        Schema::create('metodos_pagos', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nombre')->unique()->nullable();
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
        DB::table('metodos_pagos')->insert([
            ['nombre' => 'Efectivo', 'descripcion' => 'Pago en efectivo al momento de la compra'],
            ['nombre' => 'Tarjeta de Crédito', 'descripcion' => 'Pago con tarjeta de crédito'],
            ['nombre' => 'Tarjeta de Débito', 'descripcion' => 'Pago con tarjeta de débito'],
            ['nombre' => 'Transferencia Bancaria', 'descripcion' => 'Pago mediante transferencia bancaria'],
            ['nombre' => 'PayPal', 'descripcion' => 'Pago a través de PayPal'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (app()->environment('production')) {
            throw new Exception('The "down" method is disabled in production.');
        }
        Schema::dropIfExists('metodos_pagos');
    }
};
