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
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('restaurante_id')->nullable()->constrained('restaurantes');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios');
            $table->string('nombre')->nullable();
            $table->string('tipo')->nullable();
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->unsignedInteger('capacidad_total')->nullable();
            $table->time('hora_apertura')->nullable();
            $table->time('hora_cierre')->nullable();
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
        Schema::dropIfExists('sucursales');
    }
};
