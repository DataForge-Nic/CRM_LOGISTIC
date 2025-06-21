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
        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->decimal('peso_lb', 10, 2)->nullable();
            $table->decimal('volumen_pie3', 10, 2)->nullable();
            $table->decimal('tarifa_manual', 10, 2)->nullable();
            $table->unsignedBigInteger('factura_id')->nullable();
            $table->decimal('monto_calculado', 10, 2)->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->string('estado', 50)->default('en tránsito'); // estados como 'en oficina', etc.
            $table->string('numero_guia', 100)->unique();
            $table->text('notas')->nullable();
            $table->unsignedBigInteger('servicio_id')->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('factura_id')->references('id')->on('facturacion')->onDelete('set null');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};
