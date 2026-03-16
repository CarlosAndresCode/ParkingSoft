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
        // 1. Productos para Retail
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        // 2. Tipos de Lavado
        Schema::create('wash_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Básico, Polichado
            $table->decimal('price', 12, 2);
            $table->string('vehicle_type'); // e.g., Sedan, SUV, Moto
            $table->timestamps();
        });

        // 3. Transacciones (Base Común de Facturación)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique(); // Factura o Ticket
            $table->foreignId('user_id')->constrained(); // Empleado que realiza la venta
            $table->decimal('total_amount', 12, 2);
            $table->string('payment_method')->default('cash');
            $table->string('status')->default('completed'); // completed, cancelled
            $table->timestamps();
        });

        // 4. Detalles de Transacción (Polimórfico o específico)
        // Para este diseño, usaremos una tabla de items que vincula productos, lavados o parqueos.
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->morphs('serviceable'); // product, wash_service, parking_session
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // 5. Lavados Realizados
        Schema::create('washes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wash_type_id')->constrained();
            $table->string('plate')->nullable(); // Opcional según requerimiento
            $table->timestamp('completed_at')->useCurrent();
            $table->timestamps();
        });

        // 6. Actualizar parking_sessions para vincular a transacciones (opcional o mediante items)
        // Por ahora lo manejaremos a través de transaction_items.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('washes');
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('wash_types');
        Schema::dropIfExists('products');
    }
};
