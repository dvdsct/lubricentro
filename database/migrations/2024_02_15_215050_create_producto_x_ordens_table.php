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
        Schema::create('producto_x_ordens', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('orden_id');
            $table->foreign('orden_id')
            ->references('id')
            ->on('ordens')
            ->onDelete('cascade');

            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')
            ->references('id')
            ->on('productos')
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
        Schema::dropIfExists('producto_x_ordens');
    }
};
