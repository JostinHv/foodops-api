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
            $table->foreignId('categoria_menu_id')->constrained('categorias_menus');
            $table->foreignId('imagen_id')->constrained('imagenes');
            $table->string('nombre')->nullable();
            $table->string('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->unsignedInteger('orden_visualizacion')->nullable();
            $table->boolean('disponible')->default(true);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_menus');
    }
};
