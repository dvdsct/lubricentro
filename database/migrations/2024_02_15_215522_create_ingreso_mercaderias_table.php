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
        Schema::create('ingreso_mercaderias', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tipo_ingreso_mercaderias_id');
            $table->foreign('tipo_ingreso_mercaderias_id')
            ->references('id')
            ->on('tipo_ingreso_mercaderias')
            ->onDelete('cascade');

            $table->unsignedBigInteger('proveedores_id');
            $table->foreign('proveedores_id')
            ->references('id')
            ->on('proveedores')
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso_mercaderias');
    }
};
