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

            $table->unsignedBigInteger('tipo_pago_id');
            $table->foreign('tipo_pago_id')
            ->references('id')
            ->on('tipo_pagos')
            ->onDelete('cascade');

            $table->unsignedBigInteger('medio_pago_id');
            $table->foreign('medio_pago_id')
            ->references('id')
            ->on('medio_pagos')
            ->onDelete('cascade');

            $table->string('efectivo');
            $table->string('estado');



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
