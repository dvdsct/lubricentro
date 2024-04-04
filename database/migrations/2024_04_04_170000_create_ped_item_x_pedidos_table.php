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
        Schema::create('ped_item_x_pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ped_item_id');
            $table->foreign('ped_item_id')
            ->references('id')
            ->on('ped_items')
            ->onDelete('cascade');
            $table->unsignedBigInteger('pedido_proveedor_id');
            $table->foreign('pedido_proveedor_id')
            ->references('id')
            ->on('pedido_proveedors')
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
        Schema::dropIfExists('ped_item_x_pedidos');
    }
};
