<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_trackings', function (Blueprint $table) {
            $table->id('id_prescription');
            $table->foreignId('id_record')->constrained('medical_records')->onDelete('cascade');
            $table->string('medication_name');
            $table->string('dosage');
            $table->string('frequency');
            $table->string('duration');
            $table->timestamp('prescribed_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_trackings');
    }
};