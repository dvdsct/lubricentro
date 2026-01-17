<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // add numeric column to stocks and useful indexes
        Schema::table('stocks', function (Blueprint $table) {
            if (!Schema::hasColumn('stocks', 'cantidad_num')) {
                $table->decimal('cantidad_num', 12, 3)->nullable()->after('cantidad');
            }
            $table->index('producto_id');
            $table->index('sucursal_id');
        });

        // add index on productos.subcategoria_producto_id for faster grouping/filtering
        Schema::table('productos', function (Blueprint $table) {
            $table->index('subcategoria_producto_id');
        });

        // populate cantidad_num from existing string values (best-effort sanitization)
        DB::table('stocks')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                $raw = $row->cantidad;
                if (is_null($raw)) {
                    $val = null;
                } else {
                    // try to keep digits, dot and comma, then normalize comma to dot
                    $clean = preg_replace('/[^0-9,\.\-]/', '', (string)$raw);
                    $clean = str_replace(',', '.', $clean);
                    $val = is_numeric($clean) ? (float)$clean : null;
                }
                DB::table('stocks')->where('id', $row->id)->update(['cantidad_num' => $val]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            if (Schema::hasColumn('stocks', 'cantidad_num')) {
                $table->dropColumn('cantidad_num');
            }
            $table->dropIndex(['producto_id']);
            $table->dropIndex(['sucursal_id']);
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->dropIndex(['subcategoria_producto_id']);
        });
    }
};
