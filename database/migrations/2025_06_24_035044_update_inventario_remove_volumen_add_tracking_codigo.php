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
        Schema::table('inventario', function (Blueprint $table) {
            $table->dropColumn('volumen_pie3');
            $table->string('tracking_codigo', 100)->nullable()->after('peso_lb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventario', function (Blueprint $table) {
            $table->decimal('volumen_pie3', 8, 2)->nullable();
            $table->dropColumn('tracking_codigo');
        });
    }
};
