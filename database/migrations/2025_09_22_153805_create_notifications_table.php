<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->string('notification_id')->primary();
            $table->string('appointment_id');
            $table->string('status');
            $table->text('message');
            $table->dateTime('sent_at');
            
            $table->foreign('appointment_id')->references('appointment_id')->on('appointments');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};