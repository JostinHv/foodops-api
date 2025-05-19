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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants_suscripciones');
            $table->foreignId('foto_perfil_id')->nullable()->constrained('imagenes');
            $table->foreignId('restaurante_id')->nullable()->constrained('restaurantes');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('celular')->nullable();
            $table->date('ultimo_acceso')->nullable();
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
        Schema::dropIfExists('usuarios');
    }
};
