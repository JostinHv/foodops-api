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
        Schema::create('igv', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedInteger('anio')->nullable();
            $table->decimal('valor_decimal', 10, 2)->nullable();
            $table->decimal('valor_porcentaje', 10, 2)->nullable();
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
        Schema::dropIfExists('igv');
    }
};
