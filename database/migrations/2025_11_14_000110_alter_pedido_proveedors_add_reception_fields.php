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
        Schema::table('pedido_proveedors', function (Blueprint $table) {
            $table->date('fecha_ingreso_estimada')->nullable()->after('fecha_ingreso');
            $table->dateTime('fecha_recepcion')->nullable()->after('fecha_ingreso_estimada');
            $table->unsignedBigInteger('usuario_creador_id')->nullable()->after('observaciones');
            $table->unsignedBigInteger('usuario_receptor_id')->nullable()->after('usuario_creador_id');

            $table->foreign('usuario_creador_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('usuario_receptor_id')->references('id')->on('users')->nullOnDelete();
        });

        if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `pedido_proveedors` ALTER COLUMN `estado` SET DEFAULT 'borrador'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_proveedors', function (Blueprint $table) {
            // Revert added columns
            if (Schema::hasColumn('pedido_proveedors', 'fecha_ingreso_estimada')) {
                $table->dropColumn('fecha_ingreso_estimada');
            }
            if (Schema::hasColumn('pedido_proveedors', 'fecha_recepcion')) {
                $table->dropColumn('fecha_recepcion');
            }
            if (Schema::hasColumn('pedido_proveedors', 'usuario_creador_id')) {
                $table->dropForeign(['usuario_creador_id']);
                $table->dropColumn('usuario_creador_id');
            }
            if (Schema::hasColumn('pedido_proveedors', 'usuario_receptor_id')) {
                $table->dropForeign(['usuario_receptor_id']);
                $table->dropColumn('usuario_receptor_id');
            }
            // We cannot reliably revert estado change without knowing previous default; skipping.
        });
    }
};
