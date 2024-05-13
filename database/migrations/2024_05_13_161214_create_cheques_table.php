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
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banco_id');
            $table->foreign('banco_id')
            ->references('id')
            ->on('bancos')
            ->onDelete('cascade');
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

            $table->date('vencimiento');
            $table->string('monto');
            $table->string('nro_cheque')->nullable();
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
        Schema::dropIfExists('cheques');
    }
};
