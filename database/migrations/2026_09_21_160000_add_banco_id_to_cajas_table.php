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
        Schema::table('cajas', function (Blueprint $table) {
            if (!Schema::hasColumn('cajas', 'banco_id')) {
                $table->unsignedBigInteger('banco_id')->nullable()->after('sucursal_id');
                $table->foreign('banco_id')->references('id')->on('bancos')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cajas', function (Blueprint $table) {
            if (Schema::hasColumn('cajas', 'banco_id')) {
                $table->dropForeign(['banco_id']);
                $table->dropColumn('banco_id');
            }
        });
    }
};
