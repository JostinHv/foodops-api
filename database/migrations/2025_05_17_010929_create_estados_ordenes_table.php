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
        Schema::create('estados_ordenes', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nombre')->unique()->nullable();
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
        DB::table('estados_ordenes')->insert([
            ['nombre' => 'En Proceso', 'descripcion' => 'Orden en proceso', 'activo' => true],
            ['nombre' => 'Preparada', 'descripcion' => 'Orden preparada', 'activo' => true],
            ['nombre' => 'Cancelada', 'descripcion' => 'Orden cancelada', 'activo' => true],
            ['nombre' => 'Servida', 'descripcion' => 'Orden servida', 'activo' => true],
            ['nombre' => 'Solicitando Pago', 'descripcion' => 'Orden solicitando pago', 'activo' => true],
            ['nombre' => 'Pagada', 'descripcion' => 'Orden pagada', 'activo' => true],
            ['nombre' => 'En disputa', 'descripcion' => 'Orden en disputa', 'activo' => true],
            ['nombre' => 'Cerrada', 'descripcion' => 'Orden cerrada', 'activo' => true],
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
        Schema::dropIfExists('estados_ordenes');
    }
};
