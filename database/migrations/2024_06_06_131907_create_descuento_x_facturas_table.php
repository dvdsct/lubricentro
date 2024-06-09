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
        Schema::create('descuento_x_facturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factura_id');
            $table->foreign('factura_id')
            ->references('id')
            ->on('facturas')
            ->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
            $table->string('monto')->nullable();
            $table->string('estado')->default('1');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descuento_x_facturas');
    }
};
