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
        if (!Schema::hasTable('cuenta_corriente_proveedors')) {
            Schema::create('cuenta_corriente_proveedors', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('proveedor_id');
                $table->foreign('proveedor_id')->references('id')->on('proveedors')->onDelete('cascade');

                $table->date('fecha')->nullable();
                $table->string('tipo_movimiento'); // 'factura', 'nota_credito', 'pago', 'ajuste'
                $table->string('comprobante_tipo')->nullable(); // 'Factura A', 'Nota de Crédito', 'Orden de Pago', etc.
                $table->string('comprobante_numero')->nullable();
                $table->string('descripcion')->nullable();

                $table->decimal('debe', 14, 2)->default(0); // Aumenta deuda con proveedor (Facturas)
                $table->decimal('haber', 14, 2)->default(0); // Disminuye deuda con proveedor (Pagos, NC)
                $table->decimal('saldo', 14, 2)->default(0); // Saldo resultante
                $table->decimal('monto_bloqueado', 14, 2)->default(0); // Monto bloqueado por NC pendientes

                $table->unsignedBigInteger('factura_id')->nullable();
                $table->foreign('factura_id')->references('id')->on('facturas')->nullOnDelete();

                $table->unsignedBigInteger('nota_credito_id')->nullable();
                $table->foreign('nota_credito_id')->references('id')->on('nota_creditos')->nullOnDelete();

                $table->unsignedBigInteger('orden_pago_id')->nullable();
                $table->foreign('orden_pago_id')->references('id')->on('orden_pagos')->nullOnDelete();

                $table->unsignedBigInteger('pago_id')->nullable();
                $table->foreign('pago_id')->references('id')->on('pagos')->nullOnDelete();

                $table->unsignedBigInteger('pedido_proveedor_id')->nullable();
                $table->foreign('pedido_proveedor_id')->references('id')->on('pedido_proveedors')->nullOnDelete();

                $table->string('estado')->default('activo'); // 'activo', 'anulado'
                $table->softDeletes();
                $table->timestamps();

                $table->index(['proveedor_id', 'fecha']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_corriente_proveedors');
    }
};
