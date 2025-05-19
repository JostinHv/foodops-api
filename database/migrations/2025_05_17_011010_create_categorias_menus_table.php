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
        Schema::create('categorias_menus', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales');
            $table->foreignId('imagen_id')->nullable()->constrained('imagenes');
            $table->string('nombre')->nullable();
            $table->string('descripcion')->nullable();
            $table->unsignedInteger('orden_visualizacion')->nullable();
            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('categorias_menus');
    }
};
