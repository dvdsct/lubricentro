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

            $table->unsignedBigInteger('empleado_id')->nullable();
            $table->foreign('empleado_id')
            ->references('id')
            ->on('empleados')
            ->onDelete('cascade');

            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->foreign('sucursal_id')
            ->references('id')
            ->on('sucursals')
            ->onDelete('cascade');

            $table->unsignedBigInteger('servicio_id')->nullable();
            $table->foreign('servicio_id')
            ->references('id')
            ->on('servicios')
            ->onDelete('cascade');

            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')
            ->references('id')
            ->on('clientes')
            ->onDelete('cascade');

            $table->unsignedBigInteger('vehiculo_id')->nullable();
            $table->foreign('vehiculo_id')
            ->references('id')
            ->on('vehiculos')
            ->onDelete('cascade');

            $table->string('motivo');
            $table->time('horario')->nullable();
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
        Schema::dropIfExists('ordens');
    }
};
