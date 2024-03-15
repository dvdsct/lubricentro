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
        Schema::create('modelo_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');

            $table->unsignedBigInteger('tipo_vehiculo_id')->nullable();
            $table->foreign('tipo_vehiculo_id')
            ->references('id')
            ->on('tipo_vehiculos')
            ->onDelete('cascade');

            $table->unsignedBigInteger('marca_vehiculo_id');
            $table->foreign('marca_vehiculo_id')
            ->references('id')
            ->on('marca_vehiculos')
            ->onDelete('cascade');

            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelo_vehiculos');
    }
};
