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


            $table->unsignedBigInteger('modelo_vehiculo_id');
            $table->foreign('modelo_vehiculo_id')
            ->references('id')
            ->on('modelo_vehiculos')
            ->onDelete('cascade');


            $table->string('dominio')->nullable();
            $table->string('color')->nullable();
            $table->string('version')->nullable();
            $table->string('año')->nullable();
            $table->string('estado')->nullable();
            $table->softDeletes();
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
