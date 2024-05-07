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
        Schema::create('pedido_proveedors', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('proveedor_id');
            $table->foreign('proveedor_id')
            ->references('id')
            ->on('proveedors')
            ->onDelete('cascade');
            $table->unsignedBigInteger('sucursal_id');
            $table->foreign('sucursal_id')
            ->references('id')
            ->on('sucursals')
            ->onDelete('cascade');

            $table->unsignedBigInteger('tipo_pedido_id');
            $table->foreign('tipo_pedido_id')
            ->references('id')
            ->on('tipo_pedidos')
            ->onDelete('cascade');

            $table->string('descripcion')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->integer('monto_total')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('estado')->default('2');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_proveedors');
    }
};
