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
        Schema::create('pagos_x_cajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pago_id')->nullable();
            $table->foreign('pago_id')
            ->references('id')
            ->on('pagos')
            ->onDelete('cascade');
            $table->unsignedBigInteger('caja_id')->nullable();
            $table->foreign('caja_id')
            ->references('id')
            ->on('cajas')
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
        Schema::dropIfExists('pagos_x_cajas');
    }
};
