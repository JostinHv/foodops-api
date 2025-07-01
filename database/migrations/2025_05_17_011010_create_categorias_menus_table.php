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
//        DB::table('categorias_menus')->insert([
//            ['tenant_id' => null, 'sucursal_id' => null, 'imagen_id' => null, 'nombre' => 'Entradas', 'descripcion' => 'Entradas del menú', 'orden_visualizacion' => 1, 'activo' => true],
//            ['tenant_id' => null, 'sucursal_id' => null, 'imagen_id' => null, 'nombre' => 'Platos Principales', 'descripcion' => 'Platos principales del menú', 'orden_visualizacion' => 2, 'activo' => true],
//            ['tenant_id' => null, 'sucursal_id' => null, 'imagen_id' => null, 'nombre' => 'Postres', 'descripcion' => 'Postres del menú', 'orden_visualizacion' => 3, 'activo' => true],
//            ['tenant_id' => null, 'sucursal_id' => null, 'imagen_id' => null, 'nombre' => 'Bebidas', 'descripcion' => 'Bebidas del menú', 'orden_visualizacion' => 4, 'activo' => true],
//        ]);
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
