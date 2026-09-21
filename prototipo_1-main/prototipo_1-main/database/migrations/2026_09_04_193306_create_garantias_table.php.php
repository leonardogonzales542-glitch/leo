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
      Schema::create('garantias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas');
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('cliente_id')->constrained('users');

            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();

            $table->text('descripcion_falla')->nullable();

            $table->enum('estado', ['activa', 'proceso', 'resuelta', 'rechazada'])->default('activa');
            $table->enum('solucion', ['cambio', 'reparacion', 'devolucion'])->nullable();

            $table->timestamps();
        });  //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
