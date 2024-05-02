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
            $table->float('turno')->nullable();
            $table->float('monto_inicial')->nullable();
            $table->float('salidas')->nullable();
            $table->float('ingresos')->nullable();
            $table->float('transferencias')->nullable();
            $table->float('efectivo')->nullable();
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
