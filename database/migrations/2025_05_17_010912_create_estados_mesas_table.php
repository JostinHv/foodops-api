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
        Schema::create('estados_mesas', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nombre')->unique()->nullable();
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        DB::table('estados_mesas')->insert([
            ['nombre' => 'Libre', 'descripcion' => 'Mesa libre', 'activo' => true],
            ['nombre' => 'Ocupada', 'descripcion' => 'Mesa ocupada', 'activo' => true],
            ['nombre' => 'Reservada', 'descripcion' => 'Mesa reservada', 'activo' => true],
            ['nombre' => 'Sucia', 'descripcion' => 'Mesa sucia', 'activo' => true],
            ['nombre' => 'En Limpieza', 'descripcion' => 'Mesa en limpieza', 'activo' => true],
            ['nombre' => 'Bloqueada', 'descripcion' => 'Mesa bloqueda', 'activo' => true],
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
        Schema::dropIfExists('estados_mesas');
    }
};
