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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->constrained('sucursales');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->date('fecha_apertura');
            $table->time('hora_apertura');
            $table->decimal('monto_inicial', 10, 2);
            $table->date('fecha_cierre')->nullable();
            $table->time('hora_cierre')->nullable();
            $table->decimal('monto_final_esperado', 10, 2)->nullable()->comment('Calculado automáticamente por el sistema');
            $table->decimal('monto_final_real', 10, 2)->nullable()->comment('Ingresado manualmente por el cajero al cerrar la caja');
            $table->decimal('diferencia', 10, 2)->nullable()->comment('Calculado: monto_final_real - monto_final_esperado');
            $table->foreignId('estado_caja_id')->constrained('estados_caja');
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('cajas');
    }
}; 