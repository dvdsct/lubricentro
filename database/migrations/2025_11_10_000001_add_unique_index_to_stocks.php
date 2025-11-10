<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            // Avoid duplicate rows per sucursal-producto
            if (!Schema::hasColumn('stocks', 'sucursal_id') || !Schema::hasColumn('stocks', 'producto_id')) {
                return;
            }
            $table->unique(['sucursal_id', 'producto_id'], 'stocks_sucursal_producto_unique');
        });
    }

    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropUnique('stocks_sucursal_producto_unique');
        });
    }
};
