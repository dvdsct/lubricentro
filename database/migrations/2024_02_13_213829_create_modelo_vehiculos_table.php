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

            $table->unsignedBigInteger('tipo_vehiculo_id');
            $table->foreign('tipo_vehiculo_id')
            ->references('id')
            ->on('tipo_vehiculo')
            ->onDelete('cascade');

            $table->unsignedBigInteger('marca_id');
            $table->foreign('marca_id')
            ->references('id')
            ->on('marca')
            ->onDelete('cascade');

            $table->string('estado');
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
