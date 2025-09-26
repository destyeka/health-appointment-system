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
        Schema::create('patients', function (Blueprint $table) {
            $table->id('id_patient');

            $table->foreignId('id_user')->constrained(table: 'user', column:'id_user')->onDelete('cascade');

            $table->string('name');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->date('date_of_birth');
            $table->string('phone')->unique();
            $table->text('address');
            $table->text('insurance_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
