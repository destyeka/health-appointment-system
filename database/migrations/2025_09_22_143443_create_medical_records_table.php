<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id('id_record'); 

            $table->foreignId('id_appointment')->constrained(table: 'appointments', column: 'id_appointment')->onDelete('cascade');
            $table->foreignId('id_prescription')->constrained(table: 'prescriptions', column: 'id_prescription')->onDelete('cascade'); //cek

            $table->string('diagnosis');
            $table->string('treatment');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
};
