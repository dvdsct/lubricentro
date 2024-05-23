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
        Schema::create('pago_tarjetas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')
            ->references('id')
            ->on('clientes')
            ->onDelete('cascade');
            $table->unsignedBigInteger('pago_id');
            $table->foreign('pago_id')
            ->references('id')
            ->on('pagos')
            ->onDelete('cascade');
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id')
            ->references('id')
            ->on('plans')
            ->onDelete('cascade');
            $table->unsignedBigInteger('caja_id');
            $table->foreign('caja_id')
            ->references('id')
            ->on('cajas')
            ->onDelete('cascade');

            $table->date('vencimiento')->nullable();
            $table->string('subtotal');
            $table->string('total');
            $table->string('nro_cupon')->nullable();
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
        Schema::dropIfExists('pago_tarjetas');
    }
};
