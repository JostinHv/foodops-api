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
        Schema::create('items_menus', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('categoria_menu_id')->nullable()->constrained('categorias_menus');
            $table->foreignId('imagen_id')->nullable()->constrained('imagenes');
            $table->string('nombre')->nullable();
            $table->string('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->unsignedInteger('orden_visualizacion')->nullable();
            $table->boolean('disponible')->default(true);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
        DB::table('items_menus')->insert([
            ['categoria_menu_id' => 1, 'imagen_id' => null, 'nombre' => 'Ensalada César', 'descripcion' => 'Ensalada fresca con pollo y aderezo César', 'precio' => 10.00, 'orden_visualizacion' => 1, 'disponible' => true, 'activo' => true],
            ['categoria_menu_id' => 2, 'imagen_id' => null, 'nombre' => 'Pasta Alfredo', 'descripcion' => 'Pasta con salsa Alfredo cremosa', 'precio' => 12.50, 'orden_visualizacion' => 2, 'disponible' => true, 'activo' => true],
            ['categoria_menu_id' => 3, 'imagen_id' => null, 'nombre' => 'Tarta de Manzana', 'descripcion' => 'Tarta de manzana casera con helado', 'precio' => 5.00, 'orden_visualizacion' => 3, 'disponible' => true, 'activo' => true],
            ['categoria_menu_id' => 4, 'imagen_id' => null, 'nombre' => 'Cerveza Artesanal', 'descripcion' => 'Cerveza artesanal local', 'precio' => 3.50, 'orden_visualizacion' => 4, 'disponible' => true, 'activo' => true],
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
        Schema::dropIfExists('items_menus');
    }
};
