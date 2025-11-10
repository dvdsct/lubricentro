<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->integer('delta'); // cambio aplicado (+/-)
            $table->integer('cantidad_anterior');
            $table->integer('cantidad_nueva');
            $table->string('motivo')->nullable();
            $table->string('referencia_type')->nullable();
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index(['producto_id','sucursal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
