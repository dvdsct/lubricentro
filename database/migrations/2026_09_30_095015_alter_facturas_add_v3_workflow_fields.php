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
        Schema::table('facturas', function (Blueprint $table) {
            if (!Schema::hasColumn('facturas', 'proveedor_id')) {
                $table->unsignedBigInteger('proveedor_id')->nullable()->after('pedido_proveedor_id');
                $table->foreign('proveedor_id')->references('id')->on('proveedors')->nullOnDelete();
            }
            if (!Schema::hasColumn('facturas', 'numero_factura')) {
                $table->string('numero_factura')->nullable()->after('proveedor_id');
            }
            if (!Schema::hasColumn('facturas', 'fecha_emision')) {
                $table->date('fecha_emision')->nullable()->after('numero_factura');
            }
            if (!Schema::hasColumn('facturas', 'fecha_vencimiento')) {
                $table->date('fecha_vencimiento')->nullable()->after('fecha_emision');
            }
            if (!Schema::hasColumn('facturas', 'monto_bloqueado')) {
                $table->decimal('monto_bloqueado', 14, 2)->default(0)->after('total');
            }
            if (!Schema::hasColumn('facturas', 'saldo_pendiente')) {
                $table->decimal('saldo_pendiente', 14, 2)->default(0)->after('monto_bloqueado');
            }
            if (!Schema::hasColumn('facturas', 'escenario_recepcion')) {
                $table->string('escenario_recepcion')->nullable()->after('saldo_pendiente');
            }
            if (!Schema::hasColumn('facturas', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('escenario_recepcion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $cols = [
                'proveedor_id', 'numero_factura', 'fecha_emision',
                'fecha_vencimiento', 'monto_bloqueado', 'saldo_pendiente',
                'escenario_recepcion', 'observaciones'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('facturas', $col)) {
                    if ($col === 'proveedor_id') {
                        $table->dropForeign(['proveedor_id']);
                    }
                    $table->dropColumn($col);
                }
            }
        });
    }
};
