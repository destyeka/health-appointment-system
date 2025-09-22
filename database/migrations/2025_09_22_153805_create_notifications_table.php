<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->string('notification_id')->primary();
            $table->string('appointment_id');
            $table->string('status');
            $table->text('message');
            $table->dateTime('sent_at');

            $table->foreignId('id_appointment')->constrained('appointments', 'id_appointment')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};