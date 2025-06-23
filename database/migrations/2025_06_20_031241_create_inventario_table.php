<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventario', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('servicio_id')->nullable();
            $table->unsignedBigInteger('factura_id')->nullable();

            $table->decimal('peso_lb', 8, 2)->nullable();
            $table->decimal('volumen_pie3', 8, 2)->nullable();
            $table->decimal('tarifa_manual', 10, 2)->nullable();
            $table->decimal('monto_calculado', 10, 2)->nullable();

            $table->date('fecha_ingreso')->nullable();
            $table->string('estado')->default('pendiente');
            $table->string('numero_guia')->nullable();
            $table->text('notas')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // Llaves foráneas
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('servicio_id')->references('id')->on('servicios')->nullOnDelete();
            $table->foreign('factura_id')->references('id')->on('facturacion')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};
