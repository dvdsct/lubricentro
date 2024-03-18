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
        Schema::create('productos_x_pedido_proveedors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_proveedor_id')->nullable();
            $table->foreign('pedido_proveedor_id')
            ->references('id')
            ->on('pedido_proveedors')
            ->onDelete('cascade');

            $table->unsignedBigInteger('productos_id');
            $table->foreign('productos_id')
            ->references('id')
            ->on('productos')
            ->onDelete('cascade');
            $table->string('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos_x_pedido_proveedors');
    }
};
