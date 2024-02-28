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
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')
            ->references('id')
            ->on('empleados')
            ->onDelete('cascade');

            $table->unsignedBigInteger('servicio_id');
            $table->foreign('servicio_id')
            ->references('id')
            ->on('servicios')
            ->onDelete('cascade');

            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')
            ->references('id')
            ->on('clientes')
            ->onDelete('cascade');

            $table->unsignedBigInteger('vehiculos_x_clientes_id');
            $table->foreign('vehiculos_x_clientes_id')
            ->references('id')
            ->on('vehiculos_x_clientes')
            ->onDelete('cascade');

            $table->string('motivo');
            $table->datetime('horario')->nullable();
            $table->string('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordens');
    }
};
