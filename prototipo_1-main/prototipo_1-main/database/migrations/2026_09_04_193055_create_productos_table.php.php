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
        // Tabla Productos:
Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores');

            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('unidad_medida')->nullable();

            $table->decimal('stock_minimo', 10, 2)->default(0);
            $table->decimal('stock_actual', 10, 2)->default(0);

            $table->decimal('precio_compra', 12, 2)->default(0);
            $table->decimal('precio_venta', 12, 2)->default(0);
            $table->decimal('margen_ganancia', 5, 2)->default(0);

            $table->date('fecha_vencimiento')->nullable();
            $table->boolean('aplica_garantia')->default(false);
            $table->integer('dias_garantia')->nullable();

            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
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
