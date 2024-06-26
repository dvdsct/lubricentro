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
        Schema::create('marca_vehiculos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tipo_vehiculo_id')->nullable();
            $table->foreign('tipo_vehiculo_id')
                ->references('id')
                ->on('tipo_vehiculos')
                ->onDelete('cascade');
            $table->string('descripcion');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marca_vehiculos');
    }
};
