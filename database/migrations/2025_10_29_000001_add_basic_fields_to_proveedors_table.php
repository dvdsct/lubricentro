<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proveedors', function (Blueprint $table) {
            $table->string('nombre_fantasia')->nullable();
            $table->string('direccion')->nullable();
            $table->string('rubro')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('proveedors', function (Blueprint $table) {
            $table->dropColumn(['nombre_fantasia','direccion','rubro','telefono','email']);
        });
    }
};
