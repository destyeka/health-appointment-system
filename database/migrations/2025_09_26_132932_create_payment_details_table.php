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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id('id_payment_detail');

            $table->foreignId('id_payment')->constrained(table: 'payments', column:'id_payment')->onDelete('cascade');

            $table->decimal('amount');
            $table->enum('method', ['cash', 'credit_card', 'bank_transfer']); // revisi ntar
            $table->enum('payment_type', ['booking', 'repayment']);
            $table->enum('status_payment', ['unpaid', 'paid'])->default('unpaid');
            // test

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_details');
    }
};
