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
        Schema::create('plan_x_tarjetas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tarjeta_id');
            $table->foreign('tarjeta_id')
            ->references('id')
            ->on('tarjetas')
            ->onDelete('cascade');
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id')
            ->references('id')
            ->on('plans')
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
        Schema::dropIfExists('plan_x_tarjetas');
    }
};
