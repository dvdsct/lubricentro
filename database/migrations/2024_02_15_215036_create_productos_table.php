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

            $table->string('descripcion');
            $table->integer('costo');
            $table->bigInteger('codigo_de_barras');
            $table->integer('precio_venta')->nullable();
            $table->string('estado');
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
