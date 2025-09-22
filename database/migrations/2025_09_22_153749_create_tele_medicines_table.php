<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telemedicine', function (Blueprint $table) {
            $table->string('id_session')->primary();
            $table->string('id_appointment');
            $table->string('session_link');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('status');

            $table->foreign('id_appointment')->references('id_appointment')->on('appointments');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telemedicine');
    }
};