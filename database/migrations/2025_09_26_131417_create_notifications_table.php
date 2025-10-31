<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('id_notification');

            $table->foreignId('id_user')->constrained(table: 'users', column: 'id_user')->onDelete('cascade');

            $table->string('status'); // isinya apa
            $table->text('message');
            $table->dateTime('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
