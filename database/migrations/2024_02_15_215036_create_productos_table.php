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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->foreign('proveedor_id')
            ->references('id')
            ->on('proveedors')
            ->onDelete('cascade');
            $table->unsignedBigInteger('categoria_productos_id')->nullable();
            $table->foreign('categoria_productos_id')
            ->references('id')
            ->on('categoria_productos')
            ->onDelete('cascade');
            $table->unsignedBigInteger('subcategoria_productos_id')->nullable();
            $table->foreign('subcategoria_productos_id')
            ->references('id')
            ->on('subcategoria_productos')
            ->onDelete('cascade');

            $table->string('descripcion')->nullable();
            $table->string('costo')->nullable();
            $table->string('codigo_de_barras')->nullable();
            $table->string('codigo')->nullable();
            $table->string('precio_venta')->nullable();
            $table->string('precio_cliente1')->nullable();
            $table->string('precio_cliente2')->nullable();
            $table->string('precio_cliente3')->nullable();
            $table->string('precio_presupuesto')->nullable();
            $table->string('estado')->default('1');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
