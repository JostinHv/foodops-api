<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipos_movimiento_caja', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });

        DB::table('tipos_movimiento_caja')->insert([
            ['nombre' => 'VENTA', 'descripcion' => 'Ingreso por venta de productos'],
            ['nombre' => 'RETIRO', 'descripcion' => 'Retiro de dinero de la caja'],
            ['nombre' => 'DEPOSITO', 'descripcion' => 'Depósito de dinero en la caja'],
            ['nombre' => 'GASTO', 'descripcion' => 'Gasto realizado desde la caja'],
            ['nombre' => 'AJUSTE', 'descripcion' => 'Ajuste manual de caja'],
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
        Schema::dropIfExists('tipos_movimiento_caja');
    }
}; 