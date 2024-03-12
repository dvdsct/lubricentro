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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orden_id');
            $table->foreign('orden_id')
            ->references('id')
            ->on('ordens')
            ->onDelete('cascade');
            $table->unsignedBigInteger('tipo_factura_id');
            $table->foreign('tipo_factura_id')
            ->references('id')
            ->on('tipo_facturas')
            ->onDelete('cascade');
            $table->string('total');
            $table->string('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
