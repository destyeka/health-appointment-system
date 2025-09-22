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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('id_appointment');
            $table->foreignId('id_patient')->constrained(table: 'patients', column: 'id_patient')->onDelete('cascade');
            $table->foreignId('id_doctor')->constrained(table: 'doctors', column: 'id_doctor')->onDelete('cascade');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['scheduled', 'on_going', 'finished', 'canceled'])->default('scheduled');
            $table->enum('consultation_type', ['offline', 'online'])->default('offline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
