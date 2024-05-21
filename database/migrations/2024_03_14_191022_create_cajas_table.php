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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cajero_id');
            $table->foreign('cajero_id')
            ->references('id')
            ->on('cajeros')
            ->onDelete('cascade');
            $table->unsignedBigInteger('sucursal_id');
            $table->foreign('sucursal_id')
            ->references('id')
            ->on('sucursals')
            ->onDelete('cascade');
            $table->string('turno')->nullable();

            $table->string('monto_inicial')->nullable();
            $table->string('gastos')->nullable();
            $table->string('venta')->nullable();
            $table->string('rendicion')->nullable();

            $table->string('transferencias')->nullable();
            $table->string('tarjetas')->nullable();
            $table->string('efectivo')->nullable();
            $table->string('cheques')->nullable();
            $table->string('cuenta_corriente')->nullable();

            $table->string('observaciones')->nullable();
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
        Schema::dropIfExists('cajas');
    }
};
