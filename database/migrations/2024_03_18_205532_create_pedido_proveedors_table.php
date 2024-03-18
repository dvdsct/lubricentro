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

            $table->string('descripcion');
            $table->date('fecha_ingreso');
            $table->integer('monto_total');
            $table->string('observaciones');
            $table->string('estado');
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
