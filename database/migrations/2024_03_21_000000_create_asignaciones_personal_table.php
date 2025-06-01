<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones_personal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('sucursal_id')->constrained('sucursales')->onDelete('cascade');
            $table->string('tipo', 50)->default('permanente'); // permanente, temporal, rotacion
            $table->text('notas')->nullable();
            $table->timestamp('fecha_asignacion');
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            // Índices
            $table->index(['tenant_id', 'usuario_id', 'activo']);
            $table->index(['tenant_id', 'sucursal_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones_personal');
    }
}; 