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
        Schema::table('nota_creditos', function (Blueprint $table) {
            if (!Schema::hasColumn('nota_creditos', 'proveedor_id')) {
                $table->unsignedBigInteger('proveedor_id')->nullable()->after('factura_id');
                $table->foreign('proveedor_id')->references('id')->on('proveedors')->nullOnDelete();
            }
            if (!Schema::hasColumn('nota_creditos', 'pedido_proveedor_id')) {
                $table->unsignedBigInteger('pedido_proveedor_id')->nullable()->after('proveedor_id');
                $table->foreign('pedido_proveedor_id')->references('id')->on('pedido_proveedors')->nullOnDelete();
            }
            if (!Schema::hasColumn('nota_creditos', 'numero')) {
                $table->string('numero')->nullable()->after('pedido_proveedor_id');
            }
            if (!Schema::hasColumn('nota_creditos', 'fecha_emision')) {
                $table->date('fecha_emision')->nullable()->after('numero');
            }
            if (!Schema::hasColumn('nota_creditos', 'monto_aplicado')) {
                $table->decimal('monto_aplicado', 14, 2)->default(0)->after('monto');
            }
            if (!Schema::hasColumn('nota_creditos', 'estado')) {
                $table->string('estado')->default('pendiente_emision')->after('monto_aplicado');
                // 'pendiente_emision', 'recibida', 'aplicada', 'anulada'
            }
            if (!Schema::hasColumn('nota_creditos', 'motivo')) {
                $table->text('motivo')->nullable()->after('estado');
            }
            if (!Schema::hasColumn('nota_creditos', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('motivo');
            }
            if (!Schema::hasColumn('nota_creditos', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nota_creditos', function (Blueprint $table) {
            $cols = [
                'proveedor_id', 'pedido_proveedor_id', 'numero',
                'fecha_emision', 'monto_aplicado', 'estado',
                'motivo', 'observaciones', 'deleted_at'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('nota_creditos', $col)) {
                    if ($col === 'proveedor_id') {
                        $table->dropForeign(['proveedor_id']);
                    }
                    if ($col === 'pedido_proveedor_id') {
                        $table->dropForeign(['pedido_proveedor_id']);
                    }
                    if ($col === 'deleted_at') {
                        $table->dropSoftDeletes();
                    } else {
                        $table->dropColumn($col);
                    }
                }
            }
        });
    }
};
