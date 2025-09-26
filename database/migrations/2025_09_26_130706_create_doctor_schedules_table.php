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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id('id_doctor_schedule');

            $table->foreignId('id_doctor')->constrained(table: 'doctors', column: 'id_doctor')->onDelete('cascade');

            $table->string('day');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedTinyInteger('patient_slot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
