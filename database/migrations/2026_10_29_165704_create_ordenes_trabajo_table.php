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
        Schema::create('ordenes_trabajo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehiculo_id');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // Mecánico / operador
            $table->date('fecha')->nullable();

            // Kilómetros
            $table->string('km_entrada')->nullable();
            $table->string('km_proximo_service')->nullable();

            // Trabajo
            $table->text('trabajo_realizar')->nullable();

            // Aceite de motor
            $table->string('tipo_aceite')->nullable();
            $table->string('litros_aceite')->nullable();
            $table->boolean('aceite_envasado')->default(false);
            $table->boolean('aceite_suelto')->default(false);
            $table->boolean('aceite_lavado')->default(false);

            // Filtros
            $table->boolean('filtro_aceite')->default(false);
            $table->string('filtro_aceite_detalle')->nullable();

            $table->boolean('filtro_combustible')->default(false);
            $table->string('filtro_combustible_detalle')->nullable();

            $table->boolean('filtro_aire')->default(false);
            $table->string('filtro_aire_detalle')->nullable();

            $table->boolean('filtro_habitaculo')->default(false);
            $table->string('filtro_habitaculo_detalle')->nullable();

            // Controles de inspección general (JSON)
            $table->json('controles')->nullable();

            // Cierre y observaciones
            $table->text('observaciones')->nullable();
            $table->text('recibir_producto')->nullable();
            $table->string('operador_responsable')->nullable();
            $table->boolean('control_final')->default(false);

            $table->timestamps();

            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_trabajo');
    }
};
