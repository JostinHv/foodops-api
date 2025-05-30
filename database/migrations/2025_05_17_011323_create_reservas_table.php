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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('mesa_id')->nullable()->constrained('mesas');
            $table->foreignId('recepcionista_id')->nullable()->constrained('usuarios');
            $table->string('nombre_cliente')->nullable();
            $table->string('email_cliente')->nullable();
            $table->string('telefono_cliente')->nullable();
            $table->unsignedInteger('tamanio_grupo')->nullable();
            $table->date('fecha_reserva')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->string('notas')->nullable();
            $table->string('estado')->nullable();
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
        Schema::dropIfExists('reservas');
    }
};
