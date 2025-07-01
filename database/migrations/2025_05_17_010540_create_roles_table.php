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
        Schema::create('roles', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nombre')->unique();
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['nombre' => 'superadmin', 'descripcion' => 'Rol de super administrador del sistema', 'activo' => true],
            ['nombre' => 'administrador', 'descripcion' => 'Rol de administrador del sistema', 'activo' => true],
            ['nombre' => 'gerente', 'descripcion' => 'Rol de gerente del restaurante', 'activo' => true],
//            ['nombre' => 'cajero', 'descripcion' => 'Rol de cajero', 'activo' => true],
//            ['nombre' => 'cocinero', 'descripcion' => 'Rol de cocinero', 'activo' => true],
            ['nombre' => 'mesero', 'descripcion' => 'Rol de mesero', 'activo' => true],
            ['nombre' => 'cliente', 'descripcion' => 'Rol de cliente', 'activo' => true],
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
        Schema::dropIfExists('roles');
    }
};
