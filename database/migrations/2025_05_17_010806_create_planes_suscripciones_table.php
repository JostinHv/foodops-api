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
        Schema::create('planes_suscripciones', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nombre')->unique()->nullable();
            $table->string('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->string('intervalo')->unique()->nullable();
            $table->json('caracteristicas')->nullable();
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
        Schema::dropIfExists('planes_suscripciones');
    }
};
