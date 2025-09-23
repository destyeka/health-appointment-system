<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telemedicines', function (Blueprint $table) {
            $table->id('id_session');

            $table->foreignId('id_appointment')->constrained(table: 'appointments', column: 'id_appointment')->onDelete('cascade');

            $table->string('session_link');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('status'); // isinya apa
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telemedicines');
    }
};