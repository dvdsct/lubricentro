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
        Schema::create('items_x_presupuestos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('presupuesto_item_id');
            $table->foreign('presupuesto_item_id')
            ->references('id')
            ->on('presupuesto_items')
            ->onDelete('cascade');
            $table->unsignedBigInteger('presupuesto_id');
            $table->foreign('presupuesto_id')
            ->references('id')
            ->on('presupuestos')
            ->onDelete('cascade');
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
        Schema::dropIfExists('items_x_presupuestos');
    }
};
