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
        Schema::create('cierres_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained('cajas');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->date('fecha_cierre');
            $table->time('hora_cierre');
            $table->decimal('monto_efectivo_contado', 10, 2)->comment('Ingresado manualmente por el cajero al cerrar la caja');
            $table->decimal('monto_tarjetas', 10, 2)->default(0)->comment('Calculado por el sistema');
            $table->decimal('monto_transferencias', 10, 2)->default(0)->comment('Calculado por el sistema');
            $table->decimal('monto_otros', 10, 2)->default(0)->comment('Calculado por el sistema');
            $table->decimal('total_ventas', 10, 2)->default(0)->comment('Calculado por el sistema');
            $table->decimal('total_retiros', 10, 2)->default(0)->comment('Calculado por el sistema');
            $table->decimal('total_depositos', 10, 2)->default(0)->comment('Calculado por el sistema');
            $table->decimal('total_gastos', 10, 2)->default(0)->comment('Calculado por el sistema');
            $table->decimal('diferencia', 10, 2)->nullable()->comment('Calculado: monto_efectivo_contado - (total_ventas + total_depositos - total_retiros - total_gastos)');
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
        Schema::dropIfExists('cierres_caja');
    }
}; 