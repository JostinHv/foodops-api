<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->string('intervalo')->nullable();
            $table->json('caracteristicas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Insertar planes predefinidos
        DB::table('planes_suscripciones')->insert([
            [
                'nombre' => 'Plan Básico',
                'descripcion' => 'Ideal para restaurantes pequeños',
                'precio' => 29.99,
                'intervalo' => 'mes',
                'caracteristicas' => json_encode([
                    'limites' => [
                        'usuarios' => 5,
                        'restaurantes' => 1,
                        'sucursales' => 1
                    ],
                    'adicionales' => [
                        'Gestión de órdenes básica',
                        'Soporte por email'
                    ]
                ]),
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Plan Profesional',
                'descripcion' => 'Para restaurantes en crecimiento',
                'precio' => 59.99,
                'intervalo' => 'mes',
                'caracteristicas' => json_encode([
                    'limites' => [
                        'usuarios' => 10,
                        'restaurantes' => 3,
                        'sucursales' => 5
                    ],
                    'adicionales' => [
                        'Gestión de órdenes avanzada',
                        'Reportes semanales',
                        'Soporte prioritario',
                    ]
                ]),
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Plan Enterprise',
                'descripcion' => 'Solución completa para cadenas de restaurantes',
                'precio' => 99.99,
                'intervalo' => 'mes',
                'caracteristicas' => json_encode([
                    'limites' => [
                        'usuarios' => 50,
                        'restaurantes' => 10,
                        'sucursales' => 20
                    ],
                    'adicionales' => [
                        'Gestión multi-sucursal',
                        'API personalizada',
                        'Soporte 24/7',
                        'Backup automático',
                    ]
                ]),
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
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
        Schema::dropIfExists('planes_suscripciones');
    }
};
