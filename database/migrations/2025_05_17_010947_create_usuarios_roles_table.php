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
        Schema::create('usuarios_roles', function (Blueprint $table) {
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->foreignId('rol_id')->constrained('roles');
            $table->boolean('activo')->default(true);
            $table->primary(['usuario_id', 'rol_id']);
            $table->timestamps();
        });

        // Obtener el ID del rol superadmin
        $superadminRolId = DB::table('roles')
            ->where('nombre', 'superadmin')
            ->value('id');

        // Obtener todos los IDs de usuarios
        $usuarioIds = DB::table('usuarios')->pluck('id');

        // Asociar todos los usuarios con el rol superadmin
        $usuariosRoles = $usuarioIds->map(function ($usuarioId) use ($superadminRolId) {
            return [
                'usuario_id' => $usuarioId,
                'rol_id' => $superadminRolId,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ];
        })->toArray();

        DB::table('usuarios_roles')->insert($usuariosRoles);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (app()->environment('production')) {
            throw new Exception('The "down" method is disabled in production.');
        }
        Schema::dropIfExists('usuarios_roles');
    }
};
