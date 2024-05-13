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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tarjeta_id');
            $table->foreign('tarjeta_id')
                ->references('id')
                ->on('tarjetas')
                ->onDelete('cascade');
            $table->string('nombre_plan');
            $table->string('descripcion_plan');
            $table->string('descuento');
            $table->string('interes');
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
        Schema::dropIfExists('plans');
    }
};
