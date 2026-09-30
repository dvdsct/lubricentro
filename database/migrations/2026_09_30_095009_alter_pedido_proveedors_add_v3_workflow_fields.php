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
            if (!Schema::hasColumn('pedido_proveedors', 'condiciones_pago')) {
                $table->string('condiciones_pago')->nullable()->after('observaciones');
            }
            if (!Schema::hasColumn('pedido_proveedors', 'autorizado_por')) {
                $table->unsignedBigInteger('autorizado_por')->nullable()->after('condiciones_pago');
                $table->foreign('autorizado_por')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('pedido_proveedors', 'fecha_autorizacion')) {
                $table->dateTime('fecha_autorizacion')->nullable()->after('autorizado_por');
            }
            if (!Schema::hasColumn('pedido_proveedors', 'autorizacion_tipo')) {
                $table->string('autorizacion_tipo')->nullable()->after('fecha_autorizacion'); // 'digital', 'fisica'
            }
            if (!Schema::hasColumn('pedido_proveedors', 'autorizacion_notas')) {
                $table->text('autorizacion_notas')->nullable()->after('autorizacion_tipo');
            }
            if (!Schema::hasColumn('pedido_proveedors', 'fecha_solicitud')) {
                $table->dateTime('fecha_solicitud')->nullable()->after('autorizacion_notas');
            }
            if (!Schema::hasColumn('pedido_proveedors', 'solicitado_medio')) {
                $table->string('solicitado_medio')->nullable()->after('fecha_solicitud'); // 'whatsapp', 'telefono', 'email', 'otro'
            }
            if (!Schema::hasColumn('pedido_proveedors', 'motivo_rechazo')) {
                $table->text('motivo_rechazo')->nullable()->after('solicitado_medio');
            }
            if (!Schema::hasColumn('pedido_proveedors', 'escenario_recepcion')) {
                $table->string('escenario_recepcion')->nullable()->after('motivo_rechazo'); // 'escenario_1', 'escenario_2', 'escenario_3', 'rechazado'
            }
            if (!Schema::hasColumn('pedido_proveedors', 'total_estimado')) {
                $table->decimal('total_estimado', 14, 2)->nullable()->default(0)->after('escenario_recepcion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_proveedors', function (Blueprint $table) {
            $cols = [
                'condiciones_pago', 'autorizado_por', 'fecha_autorizacion',
                'autorizacion_tipo', 'autorizacion_notas', 'fecha_solicitud',
                'solicitado_medio', 'motivo_rechazo', 'escenario_recepcion', 'total_estimado'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('pedido_proveedors', $col)) {
                    if ($col === 'autorizado_por') {
                        $table->dropForeign(['autorizado_por']);
                    }
                    $table->dropColumn($col);
                }
            }
        });
    }
};
