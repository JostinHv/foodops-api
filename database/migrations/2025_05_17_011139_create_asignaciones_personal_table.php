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
        Schema::create('asignaciones_personal', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios');
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales');
            $table->string('tipo')->nullable();
            $table->text('notas')->nullable();
            $table->date('fecha_asignacion')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('asignaciones_personal');
    }
};
