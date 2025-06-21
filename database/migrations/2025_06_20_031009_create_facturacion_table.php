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
        Schema::create('facturacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->date('fecha_factura');
            $table->string('numero_acta', 100);
            $table->decimal('monto_total', 10, 2);
            $table->string('moneda', 10);                  // Ej: 'USD', 'NIO'
            $table->decimal('tasa_cambio', 10, 4)->nullable(); // Solo si ≠ USD
            $table->decimal('monto_local', 10, 2)->nullable(); // Convertido a moneda local
            $table->string('estado_pago', 50);             // 'pendiente', 'parcial', 'pagado'
            $table->text('nota')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturacion');
    }
};
