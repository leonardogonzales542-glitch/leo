<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     Schema::create('devoluciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas');
            $table->foreignId('cliente_id')->constrained('users');
            $table->foreignId('user_id')->constrained('users');

            $table->date('fecha_devolucion');
            $table->text('motivo')->nullable();

            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->decimal('total_devolucion', 12, 2)->default(0);

            $table->timestamps();
        });   //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
