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
        if (!Schema::hasTable('orden_pagos')) {
            Schema::create('orden_pagos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('proveedor_id');
                $table->foreign('proveedor_id')->references('id')->on('proveedors')->onDelete('cascade');
                
                $table->string('numero')->nullable()->index();
                $table->date('fecha_emision')->nullable();
                $table->decimal('monto_total', 14, 2)->default(0);
                $table->decimal('monto_pagado', 14, 2)->default(0);
                $table->string('estado')->default('pendiente_autorizacion');
                // 'borrador', 'pendiente_autorizacion', 'autorizada', 'parcialmente_pagada', 'pagada', 'cancelada'
                
                $table->unsignedBigInteger('autorizado_por')->nullable();
                $table->foreign('autorizado_por')->references('id')->on('users')->nullOnDelete();
                $table->dateTime('fecha_autorizacion')->nullable();
                $table->string('autorizacion_tipo')->nullable(); // 'digital', 'fisica'
                $table->text('autorizacion_notas')->nullable();

                $table->boolean('resumen_conciliado')->default(false);
                $table->text('resumen_incidencias')->nullable();
                
                $table->text('observaciones')->nullable();
                $table->unsignedBigInteger('usuario_creador_id')->nullable();
                $table->foreign('usuario_creador_id')->references('id')->on('users')->nullOnDelete();

                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('orden_pago_facturas')) {
            Schema::create('orden_pago_facturas', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('orden_pago_id');
                $table->foreign('orden_pago_id')->references('id')->on('orden_pagos')->onDelete('cascade');

                $table->unsignedBigInteger('factura_id');
                $table->foreign('factura_id')->references('id')->on('facturas')->onDelete('cascade');

                $table->decimal('monto_factura', 14, 2)->default(0);
                $table->decimal('monto_imputado', 14, 2)->default(0);
                $table->decimal('monto_pagado', 14, 2)->default(0);

                $table->timestamps();
            });
        }

        Schema::table('pagos', function (Blueprint $table) {
            if (!Schema::hasColumn('pagos', 'orden_pago_id')) {
                $table->unsignedBigInteger('orden_pago_id')->nullable()->after('factura_id');
                $table->foreign('orden_pago_id')->references('id')->on('orden_pagos')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            if (Schema::hasColumn('pagos', 'orden_pago_id')) {
                $table->dropForeign(['orden_pago_id']);
                $table->dropColumn('orden_pago_id');
            }
        });

        Schema::dropIfExists('orden_pago_facturas');
        Schema::dropIfExists('orden_pagos');
    }
};
