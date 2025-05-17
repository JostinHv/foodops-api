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
        Schema::create('restaurantes', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('grupo_restaurant_id')->constrained('grupos_restaurantes');
            $table->foreignId('logo_id')->constrained('imagenes');
            $table->string('nro_ruc')->unique()->nullable();
            $table->string('nombre_legal')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('direccion')->nullable();
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->string('tipo_negocio')->nullable();
            $table->string('sitio_web_url')->nullable();
            $table->string('telefono')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurantes');
    }
};
