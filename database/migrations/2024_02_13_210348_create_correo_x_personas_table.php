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
        Schema::create('correo_x_personas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('correo_id');
            $table->foreign('correo_id')
            ->references('id')
            ->on('correos')
            ->onDelete('cascade');

            $table->unsignedBigInteger('persona_id');
            $table->foreign('persona_id')
            ->references('id')
            ->on('personas')
            ->onDelete('cascade');
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correo_x_personas');
    }
};
