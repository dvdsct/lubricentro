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
        Schema::create('item_x_ingreso_mercaderias', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ingreso_mercaderias_id');
            $table->foreign('ingreso_mercaderias_id')
            ->references('id')
            ->on('ingreso_mercaderias')
            ->onDelete('cascade');

            $table->unsignedBigInteger('items_id');
            $table->foreign('items_id')
            ->references('id')
            ->on('items')
            ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_x_ingreso_mercaderias');
    }
};
