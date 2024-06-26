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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factura_id');
            $table->foreign('factura_id')
            ->references('id')
            ->on('facturas')
            ->onDelete('cascade');

            $table->unsignedBigInteger('tipo_pago_id')->nullable();
            $table->foreign('tipo_pago_id')
            ->references('id')
            ->on('tipo_pagos')
            ->onDelete('cascade');

            $table->unsignedBigInteger('medio_pago_id')->nullable();
            $table->foreign('medio_pago_id')
            ->references('id')
            ->on('medio_pagos')
            ->onDelete('cascade');

            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')
            ->references('id')
            ->on('clientes')
            ->onDelete('cascade');
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->foreign('proveedor_id')
            ->references('id')
            ->on('proveedors')
            ->onDelete('cascade');

            $table->string('in_out')->nullable();
            $table->string('efectivo')->nullable();
            $table->string('interes')->nullable();
            $table->string('descuento')->nullable();
            $table->string('code_op')->nullable();
            $table->string('parcial')->nullable();
            $table->string('concepto')->nullable();
            $table->string('total');
            $table->string('estado');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
