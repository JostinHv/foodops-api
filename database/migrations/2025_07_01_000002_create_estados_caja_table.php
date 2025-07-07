<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estados_caja', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });

        DB::table('estados_caja')->insert([
            ['nombre' => 'ABIERTA', 'descripcion' => 'Caja abierta y operativa'],
            ['nombre' => 'CERRADA', 'descripcion' => 'Caja cerrada y sin operaciones'],
            ['nombre' => 'EN_REVISION', 'descripcion' => 'Caja en proceso de revisión o auditoría'],
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
        Schema::dropIfExists('estados_caja');
    }
}; 