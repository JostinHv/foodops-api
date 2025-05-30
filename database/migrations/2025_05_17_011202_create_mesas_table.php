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
            $table->foreignId('estado_mesa_id')->default(1)->nullable()->constrained('estados_mesas');
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales');
            $table->string('nombre')->nullable();
            $table->unsignedInteger('capacidad')->nullable();
            $table->timestamps();
        });
        DB::table('mesas')->insert([
            ['estado_mesa_id' => 1, 'sucursal_id' => null, 'nombre' => 'Mesa 1', 'capacidad' => 1],
            ['estado_mesa_id' => 1, 'sucursal_id' => null, 'nombre' => 'Mesa 2', 'capacidad' => 2],
            ['estado_mesa_id' => 1, 'sucursal_id' => null, 'nombre' => 'Mesa 3', 'capacidad' => 3],
            ['estado_mesa_id' => 1, 'sucursal_id' => null, 'nombre' => 'Mesa 4', 'capacidad' => 4],
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
        Schema::dropIfExists('mesas');
    }
};
