<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telemedicines', function (Blueprint $table) {
            $table->string('id_session')->primary();
            $table->string('id_appointment');
            $table->string('session_link');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('status');

            $table->foreignId('id_appointment')->constrained('appointments')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telemedicines');
    }
};