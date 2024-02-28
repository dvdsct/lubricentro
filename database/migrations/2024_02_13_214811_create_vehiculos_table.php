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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_vehiculo_id');
            $table->foreign('tipo_vehiculo_id')
            ->references('id')
            ->on('tipo_vehiculos')
            ->onDelete('cascade');

            $table->unsignedBigInteger('modelo_vehiculo_id');
            $table->foreign('modelo_vehiculo_id')
            ->references('id')
            ->on('modelo_vehiculos')
            ->onDelete('cascade');

            $table->unsignedBigInteger('marca_vehiculo_id');
            $table->foreign('marca_vehiculo_id')
            ->references('id')
            ->on('marca_vehiculos')
            ->onDelete('cascade');

            $table->string('dominio');
            $table->string('color');
            $table->string('version');
            $table->string('año');
            $table->string('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
