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
        Schema::create('pedido_proveedor_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pedido_proveedor_id');
            $table->foreign('pedido_proveedor_id')
                ->references('id')
                ->on('pedido_proveedors')
                ->onDelete('cascade');

            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')
                ->references('id')
                ->on('productos')
                ->onDelete('cascade');

            $table->unsignedInteger('cantidad_pedida');
            $table->unsignedInteger('cantidad_recibida')->default(0);
            $table->decimal('costo_unitario', 12, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);

            // pendiente | recibido_parcial | recibido_total
            $table->string('estado_item')->default('pendiente');

            $table->timestamps();
        });

        Schema::table('pedido_proveedor_items', function (Blueprint $table) {
            $table->index(['pedido_proveedor_id']);
            $table->index(['producto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_proveedor_items');
    }
};
