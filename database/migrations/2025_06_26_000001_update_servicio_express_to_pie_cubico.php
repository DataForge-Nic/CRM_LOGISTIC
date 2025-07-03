<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('servicios')
            ->where('tipo_servicio', 'Express')
            ->update(['tipo_servicio' => 'Pie Cúbico']);
    }

    public function down(): void
    {
        DB::table('servicios')
            ->where('tipo_servicio', 'Pie Cúbico')
            ->update(['tipo_servicio' => 'Express']);
    }
}; 