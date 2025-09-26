<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('id_payment');

            $table->foreignId('id_appointment')
                  ->constrained(table: 'appointments', column:'id_appointment') 
                  ->onDelete('cascade');

            $table->decimal('grand_total', 10, 2); 
            $table->boolean('booking_is_paid')->default(false);
            $table->boolean('repayment_is_paid')->default(false);

            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};