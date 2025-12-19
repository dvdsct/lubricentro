<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->string('operacion')->nullable()->after('motivo');
            $table->decimal('precio_unitario', 12, 2)->nullable()->after('user_id');
            $table->decimal('monto_total', 12, 2)->nullable()->after('precio_unitario');
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn(['operacion', 'precio_unitario', 'monto_total']);
        });
    }
};
