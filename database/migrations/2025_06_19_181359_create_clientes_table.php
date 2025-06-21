<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('clientes', function (Blueprint $table) {
           $table->id();
           $table->string('nombre_completo');
           $table->string('correo');
           $table->string('telefono', 50);
           $table->text('direccion')->nullable();
           $table->string('tipo_cliente', 50); // normal, subagencia
           $table->timestamp('fecha_registro')->useCurrent(); // ✅ Corregido
           $table->unsignedBigInteger('created_by')->nullable();
           $table->unsignedBigInteger('updated_by')->nullable();
           $table->timestamps();

           $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
           $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
