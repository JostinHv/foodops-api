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
        Schema::create('mesas', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('estado_mesa_id')->constrained('estados_mesas');
            $table->foreignId('sucursal_id')->constrained('sucursales');
            $table->string('nombre')->nullable();
            $table->unsignedInteger('capacidad')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
