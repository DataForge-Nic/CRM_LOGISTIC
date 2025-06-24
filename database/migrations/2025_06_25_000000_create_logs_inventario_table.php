<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs_inventario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // agente
            $table->unsignedBigInteger('inventario_id');
            $table->enum('accion', ['crear', 'editar']);
            $table->json('antes')->nullable(); // solo para editar
            $table->json('despues');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('inventario_id')->references('id')->on('inventario')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs_inventario');
    }
}; 