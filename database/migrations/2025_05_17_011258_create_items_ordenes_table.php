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
        Schema::create('items_ordenes', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('orden_id')->constrained('ordenes');
            $table->foreignId('item_menu_id')->constrained('items_menus');
            $table->unsignedInteger('cantidad');
            $table->decimal('monto', 10, 2)->nullable();
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
        Schema::dropIfExists('items_ordenes');
    }
};
