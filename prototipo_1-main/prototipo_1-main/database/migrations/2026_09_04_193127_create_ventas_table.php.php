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
      Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('users');
            $table->foreignId('vendedor_id')->constrained('users');

            $table->string('numero_factura')->unique();
            $table->date('fecha_venta');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('iva', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta', 'credito'])->nullable();
            $table->enum('estado', ['pagada', 'pendiente', 'anulada'])->default('pagada');

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
