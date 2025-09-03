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
        ///gastos
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('concept');
            $table->decimal('amount', 12, 2);
            $table->date('date'); // Fecha real de gasto
            $table->boolean('is_paid')->default(true); // Estado de pago
            $table->boolean('is_recurring')->default(false); // Estado de gasto recurrente
            $table->integer('installments')->default(1); // cantidad de cuotas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
