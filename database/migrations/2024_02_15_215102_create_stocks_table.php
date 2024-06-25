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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')
                ->references('id')
                ->on('productos')
                ->onDelete('cascade');
            $table->unsignedBigInteger('sucursal_id');
            $table->foreign('sucursal_id')
                ->references('id')
                ->on('sucursals')
                ->onDelete('cascade');

            $table->string('cantidad')->nullable();
            $table->string('unidad')->nullable();
            $table->integer('ideal')->nullable();
            $table->integer('escaso')->nullable();
            $table->string('estado')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
